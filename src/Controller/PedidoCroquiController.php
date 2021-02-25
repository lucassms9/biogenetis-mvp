<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PedidoCroqui Controller
 *
 * @property \App\Model\Table\PedidoCroquiTable $PedidoCroqui
 *
 * @method \App\Model\Entity\PedidoCroqui[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidoCroquiController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Croquis', 'Pedidos'],
        ];
        $pedidoCroqui = $this->paginate($this->PedidoCroqui);

        $this->set(compact('pedidoCroqui'));
    }

    /**
     * View method
     *
     * @param string|null $id Pedido Croqui id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedidoCroqui = $this->PedidoCroqui->get($id, [
            'contain' => ['Croquis', 'Pedidos', 'PedidoCroquiValores'],
        ]);

        $this->set('pedidoCroqui', $pedidoCroqui);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pedidoCroqui = $this->PedidoCroqui->newEntity();
        if ($this->request->is('post')) {
            $pedidoCroqui = $this->PedidoCroqui->patchEntity($pedidoCroqui, $this->request->getData());
            if ($this->PedidoCroqui->save($pedidoCroqui)) {
                $this->Flash->success(__('The pedido croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido croqui could not be saved. Please, try again.'));
        }
        $croquiTipos = $this->PedidoCroqui->Croquis->find('list', ['limit' => 200]);
        $pedidos = $this->PedidoCroqui->Pedidos->find('list', ['limit' => 200]);
        $this->set(compact('pedidoCroqui', 'croquiTipos', 'pedidos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido Croqui id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pedidoCroqui = $this->PedidoCroqui->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pedidoCroqui = $this->PedidoCroqui->patchEntity($pedidoCroqui, $this->request->getData());
            if ($this->PedidoCroqui->save($pedidoCroqui)) {
                $this->Flash->success(__('The pedido croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido croqui could not be saved. Please, try again.'));
        }
        $croquiTipos = $this->PedidoCroqui->Croquis->find('list', ['limit' => 200]);
        $pedidos = $this->PedidoCroqui->Pedidos->find('list', ['limit' => 200]);
        $this->set(compact('pedidoCroqui', 'croquiTipos', 'pedidos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido Croqui id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pedidoCroqui = $this->PedidoCroqui->get($id);
        if ($this->PedidoCroqui->delete($pedidoCroqui)) {
            $this->Flash->success(__('The pedido croqui has been deleted.'));
        } else {
            $this->Flash->error(__('The pedido croqui could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
