<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

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

        $this->sexos = [
            'M' => 'M',
            'F' => 'F'
        ];
        $this->formas_pagamento = [
            'Cartão de Credito' => 'Cartão de Credito',
            'Cartão de Débito' => 'Cartão de Débito',
            'Dinheiro' => 'Dinheiro'
        ];

        $this->loadComponent('Helpers');
        $this->loadModel('Anamneses');
        $this->loadModel('Vouchers');
        $this->loadModel('ExtratoSaldo');
        $this->loadModel('EntradaExames');
        $this->loadModel('Croquis');
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

        if ($pedido && $pedido->entrada_exame && $pedido->anamnese) {
            $barcode = [
                'tipo_exame' => $pedido->entrada_exame->tipo_exame,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'paciente_data_nasc' => $pedido->anamnese->paciente->data_nascimento->i18nFormat('dd/MM/yyyy'),
                'data_sistema' => $pedido->created->i18nFormat('dd/MM/yyyy'),
                'codigo_pedido' => $pedido->codigo_pedido,
            ];

            $retorno['error'] = 0;
            $retorno['barcode'] = $barcode;
        }

        echo json_encode($retorno);
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

    public function index()
    {
        $action = 'Ver Todos';
        $title = 'Atendimento';

        $conditions = [];

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


    public function showpedido($id, $tab_current = 'paciente')
    {
        $action = 'Detalhe';
        $title = 'Pedido';

        $conditions = [];

        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras'],
        ]);

        $paciente = $pedido->anamnese->paciente;
        $anamnese = $pedido->anamnese;

        $pagamento = $pedido->entrada_exame;

        $sexos = $this->sexos;

        $exames_tipos = $this->EntradaExames->find('list');
        $useForm = true;
        $croqui = null;
        $croqui_tipos = $this->Croquis->find('list');
        $formas_pagamento = $this->formas_pagamento;

        $this->set(compact('action', 'title', 'pedido', 'tab_current', 'sexos', 'paciente', 'anamnese', 'pagamento', 'exames_tipos', 'useForm', 'croqui', 'croqui_tipos', 'formas_pagamento'));
    }

    public function pagamento($pedido_id = null)
    {
        $req = $this->request->getData();
        // debug($req);
        // die;
        if ((!empty($req['voucher_cod']) || !empty($req['formas_pagamento'])) && !empty($req['pedido_id']) && !empty($req['entrada_exame_id'])) {

            $pedido = $this->Pedidos->get($req['pedido_id'], [
                'contain' => ['EntradaExames']
            ]);

            //pagamento sem voucher
            if (!empty($req['formas_pagamento']) && empty($req['voucher_cod'])) {
                $pedido->forma_pagamento = $req['formas_pagamento'];
                $pedido->entrada_exame_id = $req['entrada_exame_id'];
                $pedido->status = 'EmTriagem';
                $this->Pedidos->save($pedido);
            } else {

                $id_valid = $this->Vouchers->find('all', [
                    'conditions' => ['codigo' => $req['voucher_cod'], 'used' => 0]
                ])->first();

                if (!empty($id_valid)) {
                    $pedido->voucher_id = $id_valid->id;
                    $pedido->forma_pagamento = 'Voucher';
                    $pedido->entrada_exame_id = $req['entrada_exame_id'];
                    $pedido->status = 'EmTriagem';
                    $this->Pedidos->save($pedido);

                    $entrada_exame = $this->EntradaExames->get($req['entrada_exame_id']);

                    $save_extrato = $this->ExtratoSaldo->newEntity([
                        'voucher_id' => $id_valid->id,
                        'type' => 'D',
                        'valor' => $entrada_exame->valor_laboratorio_conveniado,
                        'created_by' => $this->Auth->user()->id,
                    ]);
                    $save_extrato = $this->ExtratoSaldo->save($save_extrato);

                    $this->Flash->success(__('Voucher Inserido com sucesso!'));
                } else {
                    $this->Flash->error(__('Voucher Inválido!'));
                }
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
}
