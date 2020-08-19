<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PedidoCroquiValores Controller
 *
 * @property \App\Model\Table\PedidoCroquiValoresTable $PedidoCroquiValores
 *
 * @method \App\Model\Entity\PedidoCroquiValore[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidoCroquiValoresController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['PedidoCroquis'],
        ];
        $pedidoCroquiValores = $this->paginate($this->PedidoCroquiValores);

        $this->set(compact('pedidoCroquiValores'));
    }

    /**
     * View method
     *
     * @param string|null $id Pedido Croqui Valore id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedidoCroquiValore = $this->PedidoCroquiValores->get($id, [
            'contain' => ['PedidoCroquis'],
        ]);

        $this->set('pedidoCroquiValore', $pedidoCroquiValore);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pedidoCroquiValore = $this->PedidoCroquiValores->newEntity();
        if ($this->request->is('post')) {
            $pedidoCroquiValore = $this->PedidoCroquiValores->patchEntity($pedidoCroquiValore, $this->request->getData());
            if ($this->PedidoCroquiValores->save($pedidoCroquiValore)) {
                $this->Flash->success(__('The pedido croqui valore has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido croqui valore could not be saved. Please, try again.'));
        }
        $pedidoCroquis = $this->PedidoCroquiValores->PedidoCroquis->find('list', ['limit' => 200]);
        $this->set(compact('pedidoCroquiValore', 'pedidoCroquis'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido Croqui Valore id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pedidoCroquiValore = $this->PedidoCroquiValores->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pedidoCroquiValore = $this->PedidoCroquiValores->patchEntity($pedidoCroquiValore, $this->request->getData());
            if ($this->PedidoCroquiValores->save($pedidoCroquiValore)) {
                $this->Flash->success(__('The pedido croqui valore has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido croqui valore could not be saved. Please, try again.'));
        }
        $pedidoCroquis = $this->PedidoCroquiValores->PedidoCroquis->find('list', ['limit' => 200]);
        $this->set(compact('pedidoCroquiValore', 'pedidoCroquis'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido Croqui Valore id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pedidoCroquiValore = $this->PedidoCroquiValores->get($id);
        if ($this->PedidoCroquiValores->delete($pedidoCroquiValore)) {
            $this->Flash->success(__('The pedido croqui valore has been deleted.'));
        } else {
            $this->Flash->error(__('The pedido croqui valore could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
