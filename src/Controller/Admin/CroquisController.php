<?php

namespace App\Controller\Admin;

use App\Controller\AppController;


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

    public function gerador()
    {
        $action = 'Gerador';
        $title = 'Croquis';

        $croqui_tipos = $this->Croquis->find('list');
        $croqui = null;
        $conditions = ['Pedidos.status' => 'EmTriagem'];
        $query = $this->request->getQuery();

        if (!empty($query['nome_paciente']) ||  !empty($query['cpf_paciente'])){
            //Busca hashs possíveis
            $pre_query = $this->Pedidos->find('all', [
                'contain' => ['Anamneses.Pacientes'],
                'conditions' => $conditions
            ])->toList();
            $arrayfilter  = [];
            foreach ($pre_query as $key => $pedido){
                array_push($arrayfilter,  $pedido->anamnese->paciente->hash);
            }
            var_dump($arrayfilter);
            $filter_nome = '';
            if(!empty($query['nome_paciente']) ){
                $filter_nome = $query['nome_paciente'];
            }

            $filter_cpf = '';
            if(!empty($query['cpf_paciente']) ){
                $filter_cpf = $query['cpf_paciente'];
            }
            
            $users_found = json_decode($this->PacientesData->getByCpfOrNameCroqui($arrayfilter,$filter_cpf,$filter_nome), true);
            $arr_user_hashs = [];
            foreach ($users_found as $key => $usersfound ){
                array_push( $arr_user_hashs, $usersfound['hash']);
            }
            $conditions['Pacientes.hash in'] = $arr_user_hashs;
        }

/*
        if (!empty($query['nome_paciente'])) {
            $conditions['Pacientes.nome like'] = '%' . $query['nome_paciente'] . '%';
        }

        if (!empty($query['cpf_paciente'])) {
            $conditions['Pacientes.cpf'] = $query['cpf_paciente'];
        }
*/
        if (!empty($query['date_de'])) {
            $data_de = implode('-', array_reverse(explode('/', $query['date_de'])));
            $conditions['cast(Pedidos.created as date) >='] = $data_de;
        }

        if (!empty($query['date_ate'])) {
            $data_ate = implode('-', array_reverse(explode('/', $query['data_ate'])));
            $conditions['cast(Pedidos.created as date) <='] = $data_ate;
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
                    'codigo_croqui' => $this->Auth->user('cliente_id') . $date_init
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

                $getPedido->status = 'EmDiagnostico';
                $getPedido = $this->Pedidos->save($getPedido);
            }

            if (!$pedidos_encontrados) {
                $this->Flash->error('Verifique os pedidos inseridos no croqui.');
            } else {
                $this->Flash->success('Croqui Criado com sucesso!');
                return $this->redirect(['controller' => 'pedidos', 'action' => 'croquis']);
            }
        }

        $this->set(compact('croqui_tipos', 'action', 'title', 'croqui', 'pedidos_triagem'));
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
