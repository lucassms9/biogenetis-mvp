<?php

namespace App\Controller\Admin;

use Cake\Http\Client;
use App\Controller\AppController;
use App\Component\PacientesDataComponent;

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

        $this->loadComponent('PacientesData');
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

        $conditions = [];

        if ($this->Auth->user('user_type_id') != 1) {
            $conditions['Anamneses.cliente_id'] = $this->Auth->user('cliente_id');
        }

        $this->paginate = [
            'contain' => ['Anamneses'],
            'conditions' => $conditions,
            'group' => ['Anamneses.paciente_id']
        ];

        $pacientes = $this->paginate($this->Pacientes);

        $pacientesData = $this->PacientesData;
        $this->set(compact('pacientes', 'action', 'title', 'pacientesData'));
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

            if (!empty($req['data_nascimento'])) {
                $req['data_nascimento'] = implode('-', array_reverse(explode('/', $req['data_nascimento'])));
            }

            if (!empty($req['data_primeiros_sintomas'])) {
                $req['data_primeiros_sintomas'] = implode('-', array_reverse(explode('/', $req['data_primeiros_sintomas'])));
            }

            if (!empty($req['data_internacao'])) {
                $req['data_internacao'] = implode('-', array_reverse(explode('/', $req['data_internacao'])));
            }

            if (!empty($req['data_alta_hospitalar'])) {
                $req['data_alta_hospitalar'] = implode('-', array_reverse(explode('/', $req['data_alta_hospitalar'])));
            }

            if (!empty($req['data_isolamento'])) {
                $req['data_isolamento'] = implode('-', array_reverse(explode('/', $req['data_isolamento'])));
            }

            if (!empty($req['paciente_historico_viagem_14_dias_data_chegada'])) {
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
            if (!$validacao) {
                $this->Flash->error(
                    __('Preencha todos os campos obrigatórios.') .
                        "<div id=\"flash_erros\" style=\"display:none\">" . json_encode($validacao['erros']) . "</div>",
                    ['escape' => false]
                );
            } else {

                $result = $this->createdPaciente($req, $paciente);
                if (strlen($result->hash) > 5) {
                    $save_ok = true;
                    if (true) {

                        $req['paciente_id'] = $result->id;
                        $user = $this->Auth->user();

                        $req['tipo_pagamento'] = $user->cliente->tipo_cobranca;

                        $dados_anamnese = $req;
                        $dados_anamnese['status'] = 'completed';

                        $anamnese = $this->Anamneses->newEntity();
                        $anamnese = $this->Anamneses->patchEntity($anamnese, $dados_anamnese);
                        $anamnese = $this->Anamneses->save($anamnese);

                        if ($anamnese) {
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
                            if (!$pedido) {
                                echo "PEDDDDDDD";
                                $save_ok = false;
                            }
                        } else {
                            var_dump($anamnese);
                            echo "TEEEEEEEEEDDDDDDD";
                            $save_ok = false;
                        }
                    }

                    if ($save_ok) {
                        $this->Flash->success(__('Anamnese Criada com sucesso.'));
                        return $this->redirect(['controller' => 'pedidos', 'action' => 'index', 'status' => 'EmAtendimento']);
                    } else {
                        var_dump($result);
                        die;
                        $this->Flash->error(__('Erro ao salvar dados #32!'));
                    }
                } else {
                    $this->Flash->error(__('The paciente could not be saved. Please, try again.'  . $resp_code));
                }
            }
        }
        $sexos = $this->sexos;
        $disabled_inputs = true;
        $useForm = true;

        $query = $this->request->getQuery();
        $anamnese = null;
        if (!empty($query['tipo']) && $query['tipo'] === 'new') {
            $disabled_inputs = false;
        }

        if ((!empty($query['tipo']) && $query['tipo'] === 'new') || !isset($query['tipo'])) {
            $useForm = false;
        }



        if (!empty($query['paciente_cpf']) || !empty($query['paciente_nome'])) {
            $paciente_data = $this->PacientesData->getByFilter($query['paciente_nome'], $query['paciente_cpf']);
            if ($paciente_data) {
                $json_paciente = json_decode($paciente_data);
                $condition_find = [
                    'Anamneses.status' => 'created',
                    'Pacientes.hash' => $json_paciente->hash,
                ];
                $anamnese = $this->Anamneses->find('all', [
                    'contain' => ['Pacientes'],
                    'conditions' => $condition_find
                ])->first();



                if (!empty($anamnese)) {
                    $paciente = $anamnese->paciente;
                    $paciente->set('nome',  $json_paciente->nome);
                    $paciente->set('cpf',  $json_paciente->cpf);
                    $paciente->set('rg', $json_paciente->rg);
                    $paciente->set('sexo', $json_paciente->sexo);
                    $paciente->set('email', $json_paciente->email);
                    $paciente->set('celular',  $json_paciente->celular);
                    $paciente->set('telefone', $json_paciente->telefone);
                    $paciente->set(
                        'data_nascimento',
                        date("d/m/Y", strtotime($json_paciente->data_nascimento))
                    );
                    $paciente->set('cep', $json_paciente->cep);
                    $paciente->set('endereco', $json_paciente->endereco);
                    $paciente->set('bairro', $json_paciente->bairro);
                    $paciente->set('cidade', $json_paciente->cidade);
                    $paciente->set('uf', $json_paciente->uf);
                    $paciente->set('nome_da_mae', $json_paciente->nome_da_mae);
                    $paciente->set('nacionalidade', $json_paciente->nacionalidade);
                    $paciente->set('pais_residencia', $json_paciente->pais_residencia);
                    $paciente->set('foto_perfil_url', $json_paciente->foto_perfil_url);
                    $paciente->set('foto_doc_url', $json_paciente->foto_doc_url);
                    $disabled_inputs = false;
                } else {
                    $this->Flash->error('Paciente Não Encontrado', ['key' => 'filterpaciente']);
                }
            } else {
                $this->Flash->error('Paciente Não Encontrado', ['key' => 'filterpaciente']);
            }
        }

        $this->set(compact('paciente', 'action', 'title', 'sexos', 'disabled_inputs', 'anamnese', 'useForm', 'query'));
    }

    public function naoVazios($campos)
    {
        return $validacao['errors'] = true;
    }

    public function createdPaciente($req, $paciente)
    {
        /*
        $find_paciente = $this->Pacientes->find('all',[
            'conditions' => ['cpf' => $req['cpf']]
        ])->first();
        */

        $resPaciente  = $this->PacientesData->getCheckCPF($req['cpf']);

        if (!empty($resPaciente)) {
            $user_data =  json_decode($resPaciente);
            $find_paciente = $this->Pacientes->find('all', [
                'conditions' => ['hash' => $user_data->hash]
            ])->first();
            $user_data->id =  $find_paciente->id;
            return  $user_data;
        } else {
            $API_ROOT = env('USER_ENDPOINT');
            $data = array(
                "nome"        =>  $req['nome'], "cpf"         =>  $req['cpf'], "rg"          =>  $req['rg'], "email"       =>  $req['email'], "celular"     =>  $req['celular'], "telefone"    =>  $req['telefone'], "sexo"        =>  $req['sexo'], "data_nascimento" =>  $req['data_nascimento'], "endereco"        =>  $req['endereco'], "bairro"          =>  $req['bairro'], "cep"             =>  $req['cep'], "cidade"          =>  $req['cidade'], "uf"              =>  $req['uf'], "foto_perfil_url"   =>  $req['foto_perfil_url'], "foto_doc_url"      =>  $req['foto_doc_url'], "nome_da_mae"       =>  $req['nome_da_mae'], "nacionalidade"     =>  $req['nacionalidade'], "pais_residencia"   =>  $req['pais_residencia']
            );
            $json = json_encode($data);
            $http = new Client();
            $response = $http->post($API_ROOT . 'paciente/create', $json, [
                'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($json)]
            ]);
            $resp_code = $response->getStatusCode();
            $jst_r  = json_decode($response->body);
            $req['hash'] = $jst_r->hash;

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

        $this->set(compact('paciente', 'action', 'title', 'sexos'));
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
