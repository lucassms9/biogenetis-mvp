<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Clientes Controller
 *
 * @property \App\Model\Table\ClientesTable $Clientes
 *
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->cobranca_tipos = [
            'Particular' => 'Particular',
            'Convênio' => 'Convênio'
        ];
        $this->loadModel('ExtratoSaldo');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $action = 'Ver Todos';
        $title = 'Clientes';

        $clientes = $this->paginate($this->Clientes);

        $showActions = true;
        $this->set(compact('clientes', 'action', 'title', 'showActions'));
    }

    /**
     * View method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cliente = $this->Clientes->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('cliente', $cliente);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Clientes';

        $cliente = $this->Clientes->newEntity();
        if ($this->request->is('post')) {
            $cliente = $this->Clientes->patchEntity($cliente, $this->request->getData());
            if ($this->Clientes->save($cliente)) {
                $this->Flash->success(__('The cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente could not be saved. Please, try again.'));
        }

        $cobranca_tipos = $this->cobranca_tipos;

        $this->set(compact('cliente', 'action', 'title', 'cobranca_tipos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $action = 'Editar';
        $title = 'Clientes';

        $cliente = $this->Clientes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $req = $this->request->getData();

            if (!empty($req['img_header_url'])) {
                $ext = explode('/', $req['img_header_url']['type']);
                $name = md5($req['img_header_url']['name']) . '.' . $ext[1];

                $url = 'clientes_imgs/' . $name;

                move_uploaded_file($req['img_header_url']['tmp_name'], CLIENTES_IMGS . $name);
                $req['img_header_url'] = $url;
            }

            if (!empty($req['img_footer_url'])) {
                $ext = explode('/', $req['img_footer_url']['type']);
                $name = md5($req['img_footer_url']['name']) . '.' . $ext[1];

                $url = 'clientes_imgs/' . $name;

                move_uploaded_file($req['img_footer_url']['tmp_name'], CLIENTES_IMGS . $name);
                $req['img_footer_url'] = $url;
            }

            $cliente = $this->Clientes->patchEntity($cliente, $req);
            if ($this->Clientes->save($cliente)) {
                $this->Flash->success(__('The cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente could not be saved. Please, try again.'));
        }

        $clientes = $this->paginate($this->Clientes);

        $cobranca_tipos = $this->cobranca_tipos;

        $this->set(compact('cliente', 'action', 'title', 'cobranca_tipos'));
    }


    public function saldos($id = null)
    {
        $action = 'Saldo';
        $title = 'Clientes';


        if ($this->request->is(['patch', 'post', 'put'])) {
            $req = $this->request->getData();

            if (!empty($req['novo_saldo']) && $req['novo_saldo'] > 0) {

                $dataSave = [
                    'cliente_id' => $id,
                    'type' => 'C',
                    'valor' => $req['novo_saldo'],
                    'created_by' => $this->Auth->user('id')
                ];

                $extratoSaldo = $this->ExtratoSaldo->newEntity();
                $extratoSaldo = $this->ExtratoSaldo->patchEntity($extratoSaldo, $dataSave);
                $extratoSaldo = $this->ExtratoSaldo->save($extratoSaldo);

                return $this->redirect(['action' => 'saldos/' . $id]);
                $this->Flash->success(__('Dados salvos com sucesso'));
            }
        }

        $cliente = $this->Clientes->get($id, [
            'contain' => [],
        ]);

        $saldo = $cliente->getSaldo();

        $this->set(compact('action', 'title', 'saldo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cliente = $this->Clientes->get($id);
        if ($this->Clientes->delete($cliente)) {
            $this->Flash->success(__('The cliente has been deleted.'));
        } else {
            $this->Flash->error(__('The cliente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
