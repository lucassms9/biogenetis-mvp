<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SaldoConta Controller
 *
 * @property \App\Model\Table\SaldoContaTable $SaldoConta
 *
 * @method \App\Model\Entity\SaldoContum[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SaldoContaController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Clientes'],
        ];
        $saldoConta = $this->paginate($this->SaldoConta);

        $this->set(compact('saldoConta'));
    }

    /**
     * View method
     *
     * @param string|null $id Saldo Contum id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $saldoContum = $this->SaldoConta->get($id, [
            'contain' => ['Clientes'],
        ]);

        $this->set('saldoContum', $saldoContum);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $saldoContum = $this->SaldoConta->newEntity();
        if ($this->request->is('post')) {
            $saldoContum = $this->SaldoConta->patchEntity($saldoContum, $this->request->getData());
            if ($this->SaldoConta->save($saldoContum)) {
                $this->Flash->success(__('The saldo contum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The saldo contum could not be saved. Please, try again.'));
        }
        $clientes = $this->SaldoConta->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('saldoContum', 'clientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Saldo Contum id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $saldoContum = $this->SaldoConta->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $saldoContum = $this->SaldoConta->patchEntity($saldoContum, $this->request->getData());
            if ($this->SaldoConta->save($saldoContum)) {
                $this->Flash->success(__('The saldo contum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The saldo contum could not be saved. Please, try again.'));
        }
        $clientes = $this->SaldoConta->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('saldoContum', 'clientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Saldo Contum id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $saldoContum = $this->SaldoConta->get($id);
        if ($this->SaldoConta->delete($saldoContum)) {
            $this->Flash->success(__('The saldo contum has been deleted.'));
        } else {
            $this->Flash->error(__('The saldo contum could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
