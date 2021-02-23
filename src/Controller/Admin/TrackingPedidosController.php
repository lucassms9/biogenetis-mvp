<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Exception;
/**
 * TrackingPedidos Controller
 *
 * @property \App\Model\Table\TrackingPedidosTable $TrackingPedidos
 *
 * @method \App\Model\Entity\TrackingPedido[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TrackingPedidosController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TrackingPedidos');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {

        $conditions = [];

        $action = 'Logs';
        $title = 'Listagem';

        $query = $this->request->getQuery();

        if(!empty($query['user_name'])){
            $conditions['Users.nome_completo like'] = '%'. $query['user_name'] .'%';
        }

        if(!empty($query['pedido_code'])){
            $conditions['codigo_pedido like'] = '%'. $query['pedido_code'] .'%';
        }

        $this->paginate = [
            'contain' => ['Users'],
            'conditions' => $conditions,
            'group' => ['status_anterior','codigo_pedido'],
            'order' => ['created' => 'asc']
        ];

        $trackingPedidos = $this->paginate($this->TrackingPedidos);

        $this->set(compact('trackingPedidos','action','title'));
    }

    public function download($id)
    {
        $track = $this->TrackingPedidos->get($id);

        $this->downloadFile(AMOSTRAS . $track->amostra_url, $track->amostra_url);
    }

    public function downloadFile($arquivo, $name){
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream;");
        header("Content-Length:".filesize($arquivo));
        header("Content-disposition: attachment; filename=".$name);
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        readfile($arquivo);
        flush();
    }

    /**
     * View method
     *
     * @param string|null $id Tracking Pedido id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $trackingPedido = $this->TrackingPedidos->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('trackingPedido', $trackingPedido);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $trackingPedido = $this->TrackingPedidos->newEntity();
        if ($this->request->is('post')) {
            $trackingPedido = $this->TrackingPedidos->patchEntity($trackingPedido, $this->request->getData());
            if ($this->TrackingPedidos->save($trackingPedido)) {
                $this->Flash->success(__('The tracking pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tracking pedido could not be saved. Please, try again.'));
        }
        $users = $this->TrackingPedidos->Users->find('list', ['limit' => 200]);
        $this->set(compact('trackingPedido', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tracking Pedido id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $trackingPedido = $this->TrackingPedidos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $trackingPedido = $this->TrackingPedidos->patchEntity($trackingPedido, $this->request->getData());
            if ($this->TrackingPedidos->save($trackingPedido)) {
                $this->Flash->success(__('The tracking pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tracking pedido could not be saved. Please, try again.'));
        }
        $users = $this->TrackingPedidos->Users->find('list', ['limit' => 200]);
        $this->set(compact('trackingPedido', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tracking Pedido id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trackingPedido = $this->TrackingPedidos->get($id);
        if ($this->TrackingPedidos->delete($trackingPedido)) {
            $this->Flash->success(__('The tracking pedido has been deleted.'));
        } else {
            $this->Flash->error(__('The tracking pedido could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
