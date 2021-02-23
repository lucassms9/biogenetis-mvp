<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;

/**
 * Croquis Controller
 *
 * @property \App\Model\Table\CroquisTable $Croquis
 *
 * @method \App\Model\Entity\Croqui[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CroquisController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->exame_tipos = [
            'SALIVA' => 'SALIVA',
            'SWAB' => 'SWAB'
        ];
        $this->equipamento_tipos = [
            'LCMS' => 'LCMS',
            'FTIR' => 'FTIR'
        ];

        $this->loadModel('Pedidos');
        $this->loadModel('PedidoCroqui');
        $this->loadModel('TrackingPedidos');
        $this->loadModel('PedidoCroquiValores');
        $this->loadComponent('PacientesData');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $action = 'Ver Todos';
        $title = 'Croquis';

        $conditions = [];

        $croquis = $this->paginate($this->Croquis, [
            'conditions' => $conditions
        ]);
        $showActions = true;
        $this->set(compact('croquis', 'action', 'title', 'showActions'));
    }
    public function getCroqui($id)
    {
        $retorno = [
            'error' => 1,
            'croqui' => []
        ];

        $croqui = $this->Croquis->get($id);

        if (!empty($croqui)) {
            $retorno['error'] = 0;
            $retorno['croqui'] = $croqui;
        }

        echo json_encode($retorno);
        exit();
    }

    public function gerador($codigo_croquiParam = null)
    {
        $action = 'Gerador';
        $title = 'Croquis';

        $query = $this->request->getQuery();

        $croqui_tipos = $this->Croquis->find('list');
        $croqui = null;
        $conditions = ['Pedidos.status' => 'EmTriagem'];
        if(!empty($query['pedido']) ){
            $pedido_id_q = $query['pedido'];
            $conditions = ['Pedidos.codigo_pedido in ' =>  $pedido_id_q];
        }

        if (!empty($query['date_de'])) {
            $data_de = implode('-', array_reverse(explode('/', $query['date_de'])));
            $conditions['cast(Pedidos.created as date) >='] = $data_de;
        }

        if (!empty($query['date_ate'])) {
            $data_ate = implode('-', array_reverse(explode('/', $query['date_ate'])));
            $conditions['cast(Pedidos.created as date) <='] = $data_ate;
        }



        if (!empty($query['nome_paciente']) ||  !empty($query['cpf'])){

            $cpf = '';
            $nome_paciente = '';

            $pre_query = $this->Pedidos->find('all', [
                'contain' => ['Anamneses.Pacientes'],
                'conditions' => $conditions
            ])->toList();



            $arrayfilter  = [];

            if(!empty($query['cpf']) ){
                $cpf = $query['cpf'];
            }

            if(!empty($query['nome_paciente']) ){
                $nome_paciente = $query['nome_paciente'];
            }

            $arr = array('hashs' => []);

            foreach ($pre_query  as $key => $pedido) {
                if(isset($pedido->anamnese)){
                    array_push($arr['hashs'], $pedido->anamnese->paciente->hash);
                }
            }

            $body = json_encode($arr);
            try{
                $pacientes_data = json_decode($this->PacientesData->getByCpfOrNameCroqui($body,$cpf,$nome_paciente), true);

                $arr_user_hashs = [];
                foreach ($pacientes_data as $key => $usersfound ){
                    array_push( $arr_user_hashs, $usersfound['hash']);
                }
            }catch(Exception $e){

            }

            if(sizeof($arr_user_hashs) > 0){
                $conditions['Pacientes.hash in'] = $arr_user_hashs;
            }else{
                $conditions['Pacientes.hash in'] = ['0'];
            }
        }



        $pedidos_triagem = $this->Pedidos->find('all', [
            'contain' => ['Anamneses.Pacientes'],
            'conditions' => $conditions
        ])->toList();
        $arr = array('hashs' => []);
        foreach ($pedidos_triagem as $key => $pedido){
            array_push($arr['hashs'],  $pedido->anamnese->paciente->hash);
        }

        $body = json_encode($arr);
        if(sizeof($arr['hashs']) > 0){
            $pacientes_data = json_decode($this->PacientesData->getPacientes($body), true);

            for($i = 0; $i < sizeof($pedidos_triagem );$i++){
                for($z = 0; $z < sizeof($pacientes_data);$z++){
                    if($pacientes_data[$z]['hash'] == $pedidos_triagem[$i]->anamnese->paciente->hash){
                        $pedidos_triagem[$i]->anamnese->paciente->nome = $pacientes_data[$z]['nome'];
                        $pedidos_triagem[$i]->anamnese->paciente->cpf = $pacientes_data[$z]['cpf'];
                        array_splice($pacientes_data,$z,1);
                        break 1;
                    }

                }
            }
        }


        if ($this->request->is('post')) {
            $req = $this->request->getData();
            $pedidos_encontrados = true;
            $croqui_dados = [];

            foreach ($req as $key => $value) {
                if (!preg_match('/croqui_tipo_id/', $key) && !preg_match('/pedidos/', $key)) {
                    $croqui_dados[] = ['codigo' => $key, 'conteudo' => $value];
                }
            }
            $date_init = date('YmdHi');

            $lastItem = $this->PedidoCroqui->find('all',[
                'order' => ['PedidoCroqui.codigo_croqui' => 'DESC'],
                'contain' => ['Pedidos'],
                'conditions' => ['Pedidos.cliente_id' => $this->Auth->user('cliente_id')],
                'group' => ['PedidoCroqui.pedido_id']
            ])->first();

            $codigo_croqui = 1;

            if(!empty($lastItem)){
                $codigo_croqui = $lastItem->codigo_croqui + 1;
            }

            foreach ($croqui_dados as $key => $croqui_dado) {

                $getPedido = $this->Pedidos->find('all', [
                    'conditions' => ['codigo_pedido' => $croqui_dado['conteudo']]
                ])->first();

                if (empty($getPedido)) {
                    $pedidos_encontrados = false;
                }

                if (!$pedidos_encontrados) {
                    break;
                }


                $pedido_croqui = $this->PedidoCroqui->newEntity();
                $pedido_croqui = $this->PedidoCroqui->patchEntity($pedido_croqui, [
                    'croqui_tipo_id' => $req['croqui_tipo_id'],
                    'pedido_id' => $getPedido->id,
                    'codigo_croqui' => $codigo_croqui
                ]);
                $pedido_croqui = $this->PedidoCroqui->save($pedido_croqui);

                foreach ($croqui_dados as $key => $croqui_dado2) {
                    $pedido_croqui_val = $this->PedidoCroquiValores->newEntity();
                    $pedido_croqui_val = $this->PedidoCroquiValores->patchEntity($pedido_croqui_val, [
                        'pedido_croqui_id' => $pedido_croqui->id,
                        'conteudo' => $croqui_dado2['conteudo'],
                        'coluna_linha' => $croqui_dado2['codigo']
                    ]);
                    $pedido_croqui_val = $this->PedidoCroquiValores->save($pedido_croqui_val);
                }


                $log = [
                    'codigo_pedido' => $pedido->codigo_pedido,
                    'user_id' => $this->Auth->user('id'),
                    'status_anterior' =>  $pedido->status,
                    'status_atual' => 'EmDiagnostico',
                ];
                $this->TrackingPedidos->createLog($log);

                $getPedido->status = 'EmDiagnostico';
                $getPedido = $this->Pedidos->save($getPedido);
            }

            if (!$pedidos_encontrados) {
                $this->Flash->error('Verifique os pedidos inseridos no croqui.');
            } else {
                $this->Flash->success('Croqui Criado com sucesso!');
                return $this->redirect(['controller' => 'croquis', 'action' => 'gerador/'.$codigo_croqui]);
            }
        }

        $this->set(compact('croqui_tipos', 'action', 'title', 'croqui', 'pedidos_triagem','codigo_croquiParam'));
    }

    /**
     * View method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $croqui = $this->Croquis->get($id, [
            'contain' => ['Equipamentos'],
        ]);

        $this->set('croqui', $croqui);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Croquis';

        $croqui = $this->Croquis->newEntity();
        if ($this->request->is('post')) {

            $req = $this->request->getData();
            $req['created_by'] = $this->Auth->user('id');
            $req['created_cliente_by'] = $this->Auth->user('cliente_id');
            $croqui = $this->Croquis->patchEntity($croqui, $req);
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }

        $equipamento_tipos = $this->equipamento_tipos;
        $exame_tipos = $this->exame_tipos;

        $this->set(compact('croqui', 'action', 'title', 'equipamento_tipos', 'exame_tipos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Croquis';

        $croqui = $this->Croquis->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $croqui = $this->Croquis->patchEntity($croqui, $this->request->getData());
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }
        $equipamento_tipos = $this->equipamento_tipos;
        $exame_tipos = $this->exame_tipos;

        $this->set(compact('croqui', 'action', 'title', 'equipamento_tipos', 'exame_tipos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $croqui = $this->Croquis->get($id);
        if ($this->Croquis->delete($croqui)) {
            $this->Flash->success(__('The croqui has been deleted.'));
        } else {
            $this->Flash->error(__('The croqui could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
