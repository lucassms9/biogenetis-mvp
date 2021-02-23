<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Component\PacientesDataComponent;
use App\Model\Entity\Paciente;
use App\Component\ExamesDataComponent;
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Pedidos Controller
 *
 * @property \App\Model\Table\PedidosTable $Pedidos
 *
 * @method \App\Model\Entity\Pedido[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidosController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['laudoWeb','generateFile']);
        $this->sexos = [
            'M' => 'M',
            'F' => 'F'
        ];
        $this->formas_pagamento = [
            'Cartão de Credito' => 'Cartão de Credito',
            'Cartão de Débito' => 'Cartão de Débito',
            'Dinheiro' => 'Dinheiro',
            'Convênio' => 'Convênio'
        ];

        $this->loadComponent('Helpers');
        $this->loadComponent('Ibge');
        $this->loadComponent('PacientesData');
        $this->loadModel('Anamneses');
        $this->loadModel('Clientes');
        $this->loadModel('Vouchers');
        $this->loadModel('LaudoJobs');
        $this->loadModel('ExtratoSaldo');
        $this->loadModel('EntradaExames');
        $this->loadModel('Croquis');
        $this->loadModel('PedidoCroqui');
        $this->loadComponent('ExamesData');
        $this->loadComponent('Email');
        $this->loadModel('TrackingPedidos');

    }

    public function checkBarCode($pedido_id = null)
    {
        $pedido = $this->Pedidos->get($pedido_id, [
            'contain' => ['Anamneses.Pacientes', 'EntradaExames'],
        ]);

        $retorno = [
            'error' => 1,
            'barcode' => ''
        ];

        $pacienteData = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);
        $pacienteData = json_decode($pacienteData);

        $pedido->anamnese->paciente = $pacienteData;

        if ($pedido && $pedido->entrada_exame && $pedido->anamnese) {

            $barcode = [
                'tipo_exame' => $pedido->entrada_exame->tipo_exame,
                'nome_exame' => $pedido->entrada_exame->nome,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'paciente_data_nasc' => date("d/m/Y", strtotime($pedido->anamnese->paciente->data_nascimento)),
                'data_sistema' => $pedido->created->i18nFormat('dd/MM/yyyy'),
                'codigo_pedido' => $pedido->codigo_pedido,
            ];

            $retorno['error'] = 0;
            $retorno['barcode'] = $barcode;
        }

        echo json_encode($retorno);
        exit;
    }

    public function getCroquiPedido($id)
    {
        $croqui_pedido = $this->PedidoCroqui->get($id, [
            'contain' => ['PedidoCroquiValores', 'CroquiTipos']
        ]);

        echo json_encode($croqui_pedido);
        exit;
    }

    public function getPedido($id)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => ['PedidoCroqui.PedidoCroquiValores', 'PedidoCroqui.CroquiTipos']
        ]);

        echo json_encode($pedido);
        exit;
    }



    public function croquiviwer($croqui_pedido_id)
    {
        $action = 'Detalhe';
        $title = 'Croquis';
        $conditions = [
            'PedidoCroqui.id' => $croqui_pedido_id
        ];

        $croqui = $this->PedidoCroqui->find('all', [
            'contain' => ['Pedidos.Anamneses.Pacientes'],
            'conditions' => $conditions,
            'group' => ['PedidoCroqui.pedido_id']
        ])->first();


        $croquis_pedidos = $this->PedidoCroqui->find('all', [
            'contain' => ['Pedidos.Anamneses.Pacientes'],
            'conditions' => ['PedidoCroqui.codigo_croqui' => $croqui->codigo_croqui]
        ])->toArray();

        $arr = array('hashs' => []);

        foreach ($croquis_pedidos as $croquis_pedido) {
            array_push($arr['hashs'], $croquis_pedido->pedido->anamnese->paciente->hash);
        }


        $body = json_encode($arr);
        $pacientes_data = json_decode($this->PacientesData->getPacientes($body), true);
        $pedidos_list = [];

        foreach ($croquis_pedidos as $key => $croquis_pedido) {
            $finded =  array_filter($pacientes_data, function ($paciente) use ($croquis_pedido) {
                return $paciente['hash'] == $croquis_pedido->pedido->anamnese->paciente->hash;
            });
            $handle = @$finded[0] ?? [];
            $paciente = new Paciente($handle);

            $croquis_pedido->pedido->anamnese->paciente = $paciente;
        }

        $croqui_tipos = $this->Croquis->find('list');

        $croqui_tipo_id = $croqui->croqui_tipo_id;

        $this->set(compact('action', 'title', 'croqui', 'croqui_tipos', 'croqui_tipo_id', 'croquis_pedidos'));
    }

    public function croquis()
    {
        $action = 'Ver Todos';
        $title = 'Croquis';

        $conditions = [
            'Pedidos.status' => 'EmDiagnostico'
        ];

        $croquis = $this->paginate($this->PedidoCroqui, [
            'contain' => ['Pedidos.Anamneses.Pacientes', 'CroquiTipos'],
            'conditions' => $conditions,
            'group' => ['Pedidos.id']
        ]);

        $this->set(compact('action', 'title', 'croquis'));
    }

    public function index()
    {
        $API_ROOT = env('USER_ENDPOINT');
        $action = 'Ver Todos';
        $title = 'Atendimento';

        $conditions = [];

        $query = $this->request->getQuery();
        $rows = [];

        if (!empty($query['status'])) {
            $conditions['Pedidos.status'] = $query['status'];
        }


        if (!empty($query['numero_pedido'])) {
            $conditions['Pedidos.codigo_pedido in'] = $query['numero_pedido'];

        }



        $pedidos = $this->paginate($this->Pedidos, [
            'contain' => ['Anamneses.Pacientes'],
            'conditions' => $conditions
        ]);

        $arr = array('hashs' => []);

        if (!empty($query['cpf']) || !empty($query['nome_paciente'])  ) {
                    $cpf = '';
                    $nome_paciente = '';
                    foreach ($pedidos  as $key => $pedido) {
                        if(isset($pedido->anamnese)){
                            array_push($arr['hashs'], $pedido->anamnese->paciente->hash);
                        }
                    }
                    if(!empty($query['cpf']) ){
                        $cpf = $query['cpf'];
                    }
                    if(!empty($query['nome_paciente']) ){
                        $nome_paciente = $query['nome_paciente'];
                    }
                    $body = json_encode($arr);
                    $pacientes_data = json_decode($this->PacientesData->getByCpfOrNameCroqui($body,$cpf,$nome_paciente), true);


                    $pedidos_list = [];

                    foreach ($pedidos as $key => $pedido) {
                        $finded =  array_filter($pacientes_data, function ($paciente) use ($pedido) {
                            return $paciente['hash'] == $pedido->anamnese->paciente->hash;
                        });
                        $handle = @$finded[0] ?? [];
                        $paciente = new Paciente($handle);

                        $pedido->anamnese->paciente = $paciente;
                    }

        }


        $arr = array('hashs' => []);

        foreach ($pedidos as $pedido) {
            if(isset($pedido->anamnese)){
                if(isset($pedido->anamnese->paciente)){
                    array_push($arr['hashs'], $pedido->anamnese->paciente->hash);
                }

            }else{

            }
        }
        $body = json_encode($arr);
        try{
            $pacientes_data = json_decode($this->PacientesData->getPacientes($body), true);
            $pedidos_list = [];
            foreach ($pedidos as $pedido) {
                if(isset($pedido->anamnese) && isset($pedido->anamnese->paciente)){
                    $user_info = $this->PacientesData->returnPaciente($pedido->anamnese->paciente->hash, $pacientes_data);
                    array_push(
                        $pedidos_list,
                        array(
                            "hash"   => $pedido->anamnese->paciente->hash, "id"     => $pedido->id, "codigo_pedido" => $pedido->codigo_pedido, "status" => $pedido->status, "cpf"    => $user_info['cpf'], "nome"    => $user_info['nome'], "celular"    => $user_info['celular'], "created" => $pedido->created
                        )
                    );
                }
            }
        }catch(Exception $e){

        }
        $this->set(compact('action', 'title', 'pedidos', 'pedidos_list'));
    }

    public function laudo($id)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras', 'Exames.Users'],
        ]);

        $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);
        $res = json_decode($resPaciente, true);

        $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);

        $pedido->anamnese->paciente = new Paciente($res);

        $cliente = $this->Clientes->find('all', [
            'conditions' => ['id' => $this->Auth->user('cliente_id')]
        ])->first();

        $footer_laudo = @$cliente->img_footer_url;
        $header_laudo = @$cliente->img_header_url;

        $this->laudoWeb($pedido->id, 1);
        $this->set(compact('action', 'title', 'pedido', 'tab_current', 'sexos', 'paciente', 'anamnese', 'pagamento', 'exames_tipos', 'useForm', 'croqui', 'croqui_tipos', 'formas_pagamento', 'header_laudo', 'footer_laudo'));
    }


    public function laudoWeb($id, $file = 0)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras', 'Exames.Users'],
        ]);

        //buscando o paciente
        $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);

        $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);

        $res = json_decode($resPaciente, true);

        $pedido->anamnese->paciente = new Paciente($res);

        $cliente = $this->Clientes->find('all', [
            'conditions' => ['id' => $pedido->cliente_id]
        ])->first();

        $footer_laudo = @$cliente->img_footer_url;
        $header_laudo = @$cliente->img_header_url;

        // $this->render(false);

        $this->viewBuilder()->setLayout('laudo');

        $path_absolute = WWW_ROOT;

        $path = WWW_ROOT.$pedido->exame->user->foto_assinatura_digital;

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base6_assinatura = 'data:image/' . $type . ';base64,' . base64_encode($data);

        if(!empty($footer_laudo)){
            $path = WWW_ROOT.$footer_laudo;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $footer_laudo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        if(!empty($header_laudo)){
            $path = WWW_ROOT.$header_laudo;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $header_laudo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $this->set(compact('pedido', 'footer_laudo', 'header_laudo','base6_assinatura'));
        // render to a variable
        $output = $this->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();
        $options->setDefaultFont('Courier');
        $dompdf->setBasePath(realpath(WWW_ROOT . 'css'));
        $dompdf->setOptions($options);

        $dompdf->loadHtml($output);
        // $dompdf-> set_base_path(APPLICATION_PATH."/../public/themes/css/");
        // Render the HTML as PDF
        $dompdf->render();

        if($file == 1){
            $output = $dompdf->output();
            $name_pdf = LAUDO_PDF . $pedido->id . $pedido->anamnese->paciente->id .  'laudo.pdf';

            file_put_contents($name_pdf, $output);

            $data_save = [
                'completed' => 1,
                'file' => $name_pdf,
                'pedido_id' => $pedido->id
            ];
            $laudoJobs = $this->LaudoJobs->find('all',[
                'conditions' => ['pedido_id' => $pedido->id]
            ])->first();

            if(!empty($laudoJobs)){
                $laudoJobs = $this->LaudoJobs->patchEntity($laudoJobs, $data_save);
                $laudoJobs = $this->LaudoJobs->save($laudoJobs);
            }else{
                $laudoJobs = $this->LaudoJobs->newEntity();
                $laudoJobs = $this->LaudoJobs->patchEntity($laudoJobs, $data_save);
                $laudoJobs = $this->LaudoJobs->save($laudoJobs);
            }
            return true;
        }else{
            $dompdf->stream("dompdf_out.pdf",['Attachment' => false]);
        }
    }

    public function generateFile(){
        $jobs = $this->LaudoJobs->find('all',[
            'conditions' => ['completed' => 0]
        ])->toList();

        if(!empty($jobs)){
            foreach($jobs as $job){
                $this->laudoWeb($job->pedido_id, 1);
            }
        }
        echo json_encode(['success' => 1]);
       exit();
    }

    public function showpedido($id, $tab_current = 'paciente')
    {
        $action = 'Detalhe';
        $title = 'Pedido';

        $conditions = [];

        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras', 'Exames.Users', 'PedidoCroqui','LaudoJobs'],
        ]);

        $paciente = $pedido->anamnese->paciente;
        $paciente_dados  = json_decode($this->PacientesData->getByHash($paciente->hash));


        $paciente->set('nome',  $paciente_dados->nome);
        $paciente->set('cpf',  $paciente_dados->cpf);
        $paciente->set('rg', $paciente_dados->rg);
        $paciente->set('sexo', $paciente_dados->sexo);
        $paciente->set('email', $paciente_dados->email);
        $paciente->set('celular',  $paciente_dados->celular);
        $paciente->set('telefone', $paciente_dados->telefone);
        $paciente->set('data_nascimento', $paciente_dados->data_nascimento);
        $paciente->set('cep', $paciente_dados->cep);
        $paciente->set('endereco', $paciente_dados->endereco);
        $paciente->set('bairro', $paciente_dados->bairro);
        $paciente->set('cidade', $paciente_dados->cidade);
        $paciente->set('uf', $paciente_dados->uf);
        $paciente->set('nome_da_mae', $paciente_dados->nome_da_mae);
        $paciente->set('nacionalidade', $paciente_dados->nacionalidade);
        $paciente->set('pais_residencia', $paciente_dados->pais_residencia);
        $paciente->set('foto_perfil_url', $paciente_dados->foto_perfil_url);
        $paciente->set('foto_doc_url', $paciente_dados->foto_doc_url);
        $anamnese = $pedido->anamnese;

        $pagamento = $pedido->entrada_exame;

        $sexos = $this->sexos;
        $croqui_tipo_id = @$pedido->pedido_croqui->croqui_tipo_id;

        if(isset($pedido->entrada_exame_id) && $pedido->entrada_exame_id > 0){
            $exames_tipos = $this->EntradaExames->find('list',['conditions' => ['id' =>  $pedido->entrada_exame_id ]]);
        }else{
            $exames_tipos = $this->EntradaExames->find('list');
        }
        $useForm = true;
        $croqui = null;
        $croqui_tipos = $this->Croquis->find('list');
        $formas_pagamento = $this->formas_pagamento;

        $cidades_viagem = [];
        $cidades_unidade = [];

        if(!empty($anamnese->viagem_brasil_estado)){
            $cidades_find = $this->getCities($anamnese->viagem_brasil_estado);

            foreach ($cidades_find as $key => $cidade) {
                $cidades_viagem[$cidade->nome] = $cidade->nome;
            }
        }

        if(!empty($anamnese->paciente_unidade_saude_14_dias_estado)){
            $cidades_find = $this->getCities($anamnese->paciente_unidade_saude_14_dias_estado);

            foreach ($cidades_find as $key => $cidade) {
                $cidades_unidade[$cidade->nome] = $cidade->nome;
            }
        }

        $estados_find = $this->getStates();
        $estados = [];


        foreach ($estados_find as $key => $estado) {
           $estados[$estado->sigla] = $estado->sigla;
        }

        if($_SESSION['Auth']['User']['user_type_id'] == 3 || $_SESSION['Auth']['User']['user_type_id'] == 4){
            $tab_current = 'etiqueta';
        }

        $this->set(compact('action', 'title', 'croqui_tipo_id', 'pedido', 'tab_current', 'sexos', 'paciente', 'anamnese', 'pagamento', 'exames_tipos', 'useForm', 'croqui', 'croqui_tipos', 'formas_pagamento', 'paciente_dados','estados','cidades_viagem','cidades_unidade'));

    }

    public function getCities($uf)
    {
        $cities = $this->Ibge->getCity($uf);
        return $cities;
        echo json_encode($cities);
        die;
    }


    public function getStates()
    {
        $states = $this->Ibge->getStates();

        return $states;
        echo json_encode($states);
        die;
    }

    public function pagamento($pedido_id = null)
    {
        $req = $this->request->getData();


        if (!empty($req['formas_pagamento']) && !empty($req['pedido_id']) && !empty($req['entrada_exame_id']) && !empty($req['valor_exame'])) {

            $pedido = $this->Pedidos->get($req['pedido_id'], [
                'contain' => ['EntradaExames']
            ]);

            //pagamento sem voucher
            if (!empty($req['formas_pagamento'])) {

                $log = [
                    'codigo_pedido' => $pedido->codigo_pedido,
                    'user_id' => $this->Auth->user('id'),
                    'status_anterior' =>  $pedido->status,
                    'status_atual' => 'EmTriagem',
                ];
                $this->TrackingPedidos->createLog($log);

                $pedido->forma_pagamento = $req['formas_pagamento'];
                $pedido->entrada_exame_id = $req['entrada_exame_id'];

                $handle_valor = (float) $req['valor_exame'];

                $pedido->valor_exame = $handle_valor;
                $pedido->status = 'EmTriagem';
                $this->Pedidos->save($pedido);

                // } else {

                //     $id_valid = $this->Vouchers->find('all', [
                //         'conditions' => ['codigo' => $req['voucher_cod'], 'used' => 0]
                //     ])->first();

                //     if (!empty($id_valid)) {
                //         $pedido->voucher_id = $id_valid->id;
                //         $pedido->forma_pagamento = 'Voucher';
                //         $pedido->entrada_exame_id = $req['entrada_exame_id'];
                //         $pedido->status = 'EmTriagem';
                //         $this->Pedidos->save($pedido);

                //         $entrada_exame = $this->EntradaExames->get($req['entrada_exame_id']);

                //         $save_extrato = $this->ExtratoSaldo->newEntity([
                //             'voucher_id' => $id_valid->id,
                //             'type' => 'D',
                //             'valor' => $entrada_exame->valor_laboratorio_conveniado,
                //             'created_by' => $this->Auth->user()->id,
                //         ]);
                //         $save_extrato = $this->ExtratoSaldo->save($save_extrato);

                //         $this->Flash->success(__('Voucher Inserido com sucesso!'));
                //     } else {
                //         $this->Flash->error(__('Voucher Inválido!'));
                //     }
            }
        } else {
            $this->Flash->error(__('Inserir dados de pagamento!'));
        }
        return $this->redirect(['action' => 'showpedido/' . $req['pedido_id'] . '/pagamento']);
    }

    /**
     * View method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamnese', 'Amostras', 'Clientes'],
        ]);

        $this->set('pedido', $pedido);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pedido = $this->Pedidos->newEntity();
        if ($this->request->is('post')) {
            $pedido = $this->Pedidos->patchEntity($pedido, $this->request->getData());
            if ($this->Pedidos->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $anamnese = $this->Pedidos->Anamnese->find('list', ['limit' => 200]);
        $amostras = $this->Pedidos->Amostras->find('list', ['limit' => 200]);
        $clientes = $this->Pedidos->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'anamnese', 'amostras', 'clientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pedido = $this->Pedidos->patchEntity($pedido, $this->request->getData());
            if ($this->Pedidos->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $anamnese = $this->Pedidos->Anamnese->find('list', ['limit' => 200]);
        $amostras = $this->Pedidos->Amostras->find('list', ['limit' => 200]);
        $clientes = $this->Pedidos->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'anamnese', 'amostras', 'clientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pedido = $this->Pedidos->get($id);
        if ($this->Pedidos->delete($pedido)) {
            $this->Flash->success(__('The pedido has been deleted.'));
        } else {
            $this->Flash->error(__('The pedido could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function etiquetas()
    {
        $action = 'Gerar Etiquetas';
        $title = 'Pedidos';

        $conditions = [];

        if ($this->request->is('post')) {
            $req = $this->request->getData();
            debug($req);
            die;
        }

        $query = $this->request->getQuery();
        $rows = [];

        if (!empty($query['status'])) {
            $conditions['Pedidos.status'] = $query['status'];
        }

        $pedidos = $this->paginate($this->Pedidos, [
            'contain' => ['Anamneses.Pacientes'],
            'conditions' => $conditions
        ]);

        $this->set(compact('action', 'title', 'pedidos'));
    }

    public function renderEtiquetas()
    {
        $action = 'Gerar Etiquetas';
        $title = 'Pedidos';

        $conditions = [];

        if ($this->request->is('post')) {
            $req = $this->request->getData();
            $conditions['Pedidos.id in'] = $req['pedidos'];

            $pedidos = $this->Pedidos->find('all', [
                'contain' => ['Anamneses.Pacientes', 'EntradaExames'],
                'conditions' => $conditions
            ])->toArray();

            $barcodes = [];

            foreach ($pedidos as $key => $pedido) {
                if ($pedido && $pedido->entrada_exame && $pedido->anamnese) {
                    $barcodes[] = [
                        'tipo_exame' => $pedido->entrada_exame->tipo_exame,
                        'paciente_nome' => $pedido->anamnese->paciente->nome,
                        'paciente_nome' => $pedido->anamnese->paciente->nome,
                        'paciente_data_nasc' => $pedido->anamnese->paciente->data_nascimento->i18nFormat('dd/MM/yyyy'),
                        'data_sistema' => $pedido->created->i18nFormat('dd/MM/yyyy'),
                        'codigo_pedido' => $pedido->codigo_pedido,
                    ];
                }
            }
        }

        $this->set(compact('barcodes'));
    }
}
