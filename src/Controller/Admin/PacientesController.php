<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Pacientes Controller
 *
 * @property \App\Model\Table\PacientesTable $Pacientes
 *
 * @method \App\Model\Entity\Paciente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PacientesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->sexos = [
            'M' => 'M',
            'F' => 'F'
        ];

        $this->loadComponent('Helpers');
        $this->loadModel('Anamneses');
        $this->loadModel('Pedidos');

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {

        $action = 'Ver Todos';
        $title = 'Pacientes';

        $pacientes = $this->paginate($this->Pacientes);

        $this->set(compact('pacientes','action','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $paciente = $this->Pacientes->get($id, [
            'contain' => ['Exames'],
        ]);

        $this->set('paciente', $paciente);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Pacientes';

        // $anamnese = $this->Anamneses->newEntity();
        $paciente = $this->Pacientes->newEntity();
        if ($this->request->is('post')) {
            $req = $this->request->getData();

            if(!empty($req['data_nascimento'])){
                $req['data_nascimento'] = implode('-', array_reverse(explode('/', $req['data_nascimento'])));
            }

            if(!empty($req['data_primeiros_sintomas'])){
                $req['data_primeiros_sintomas'] = implode('-', array_reverse(explode('/', $req['data_primeiros_sintomas'])));
            }

            if(!empty($req['data_internacao'])){
                $req['data_internacao'] = implode('-', array_reverse(explode('/', $req['data_internacao'])));
            }

            if(!empty($req['data_alta_hospitalar'])){
                $req['data_alta_hospitalar'] = implode('-', array_reverse(explode('/', $req['data_alta_hospitalar'])));
            }

            if(!empty($req['data_isolamento'])){
                $req['data_isolamento'] = implode('-', array_reverse(explode('/', $req['data_isolamento'])));
            }

            if(!empty($req['paciente_historico_viagem_14_dias_data_chegada'])){
                $req['paciente_historico_viagem_14_dias_data_chegada'] = implode('-', array_reverse(explode('/', $req['paciente_historico_viagem_14_dias_data_chegada'])));
            }

            $validar = [
                'nome' => $req['nome'],
                'cpf' => $req['cpf'],
                'rg' => $req['rg'],
                'sexo' => $req['sexo'],
                'email' => $req['email'],
                'data_nascimento' => $req['data_nascimento'],
                'celular' => $req['celular'],
                'pais_residencia' => $req['pais_residencia'],
                'nacionalidade' => $req['nacionalidade'],
                'data_primeiros_sintomas' => $req['data_primeiros_sintomas'],
                'gestante' => $req['gestante'],
                'analgesico_antitermico_antiinflamatorio' => $req['analgesico_antitermico_antiinflamatorio'],
                'paciente_hospitalizado' => $req['paciente_hospitalizado'],
                'paciente_hospitalizado_nome_hospital' => $req['paciente_hospitalizado_nome_hospital'],

                'paciente_ventilacao_mecanica' => $req['paciente_ventilacao_mecanica'],
                'paciente_situacao_notificacao' => $req['paciente_situacao_notificacao'],
                'paciente_historico_viagem_14_dias' => $req['paciente_historico_viagem_14_dias'],
                'paciente_historico_viagem_14_dias_data_chegada' => $req['paciente_historico_viagem_14_dias_data_chegada'],

            ];

            $validacao = $this->naoVazios($validar);

            if(!$validacao){

                $this->Flash->error(
                    __('Preencha todos os campos obrigatórios.') .
                    "<div id=\"flash_erros\" style=\"display:none\">" . json_encode($validacao['erros']) . "</div>",
                    ['escape' => false]
                );

            } else {

                $result = $this->createdPaciente($req, $paciente);
                if ($result) {
                    $save_ok = true;
                    if(true){

                        $req['paciente_id'] = $result->id;
                        $user = $this->Auth->user();

                        $req['tipo_pagamento'] = $user->cliente->tipo_cobranca;

                        $dados_anamnese = $req;
                        $dados_anamnese['status'] = 'completed';

                        $anamnese = $this->Anamneses->newEntity();
                        $anamnese = $this->Anamneses->patchEntity($anamnese, $dados_anamnese);
                        $anamnese = $this->Anamneses->save($anamnese);

                        if($anamnese){
                            //criacao de pedido
                            $dadaos_pedido = [
                                'anamnese_id' => $anamnese->id,
                                'cliente_id' => $user->cliente->id,
                                'tipo_pagamento' => $user->cliente->tipo_cobranca,
                                'created_by' => $user->id,
                                'exame_entrada_id' => 1
                            ];

                            $pedido = $this->Pedidos->newEntity();
                            $pedido = $this->Pedidos->patchEntity($pedido, $dadaos_pedido);
                            $pedido = $this->Pedidos->save($pedido);
                            if(!$pedido){
                                $save_ok = false;
                            }
                        }else{
                            $save_ok = false;
                        }

                    }

                    if($save_ok){
                        $this->Flash->success(__('Anamnese Criada com sucesso.'));

                        return $this->redirect(['controller' => 'pedidos', 'action' => 'index', 'status' => 'EmAtendimento' ]);
                    }else{
                        $this->Flash->error(__('Erro ao salvar dados!'));
                    }
                }else{
                     $this->Flash->error(__('The paciente could not be saved. Please, try again.'));
                }

            }



        }
        $sexos = $this->sexos;
        $disabled_inputs = true;
        $useForm = true;

        $query = $this->request->getQuery();
        $anamnese = null;
        if(!empty($query['tipo']) && $query['tipo'] === 'new'){
            $disabled_inputs = false;

        }

        if((!empty($query['tipo']) && $query['tipo'] === 'new') || !isset($query['tipo'])){
            $useForm = false;
        }

        if(!empty($query['paciente_cpf']) || !empty($query['paciente_nome'])){
            $condition_find = [
                    'Anamneses.status' => 'created',
                    'OR' => [
                        'Pacientes.nome like' => '%'.$query['paciente_nome'].'%',
                        'Pacientes.cpf' => $this->Helpers->stringToNumber($query['paciente_cpf']),
                    ]
                ];

            $anamnese = $this->Anamneses->find('all',[
                'contain' => ['Pacientes'],
                'conditions' => $condition_find
            ])->first();
            if(!empty($anamnese)){
                $paciente = $anamnese->paciente;
                $disabled_inputs = false;
            }else{
                $this->Flash->error('Paciente Não Encontrado',['key' => 'filterpaciente']);
            }


        }

        $this->set(compact('paciente','action','title','sexos','disabled_inputs','anamnese','useForm','query'));
    }

    public function naoVazios($campos)
    {
        return $validacao['errors'] = true;
    }

    public function createdPaciente($req, $paciente)
    {

        $find_paciente = $this->Pacientes->find('all',[
            'conditions' => ['cpf' => $req['cpf']]
        ])->first();

        if(!empty($find_paciente)){
            return $find_paciente;
        } else {
            $paciente = $this->Pacientes->patchEntity($paciente, $req);
            $paciente = $this->Pacientes->save($paciente);
            return $paciente;
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Clientes';

        $paciente = $this->Pacientes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paciente = $this->Pacientes->patchEntity($paciente, $this->request->getData());
            if ($this->Pacientes->save($paciente)) {
                $this->Flash->success(__('The paciente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The paciente could not be saved. Please, try again.'));
        }
        $sexos = $this->sexos;

        $this->set(compact('paciente','action','title','sexos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $paciente = $this->Pacientes->get($id);
        if ($this->Pacientes->delete($paciente)) {
            $this->Flash->success(__('The paciente has been deleted.'));
        } else {
            $this->Flash->error(__('The paciente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
