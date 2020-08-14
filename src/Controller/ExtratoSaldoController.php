<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ExtratoSaldo Controller
 *
 * @property \App\Model\Table\ExtratoSaldoTable $ExtratoSaldo
 *
 * @method \App\Model\Entity\ExtratoSaldo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExtratoSaldoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Vouchers'],
        ];
        $extratoSaldo = $this->paginate($this->ExtratoSaldo);

        $this->set(compact('extratoSaldo'));
    }

    /**
     * View method
     *
     * @param string|null $id Extrato Saldo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $extratoSaldo = $this->ExtratoSaldo->get($id, [
            'contain' => ['Vouchers'],
        ]);

        $this->set('extratoSaldo', $extratoSaldo);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $extratoSaldo = $this->ExtratoSaldo->newEntity();
        if ($this->request->is('post')) {
            $extratoSaldo = $this->ExtratoSaldo->patchEntity($extratoSaldo, $this->request->getData());
            if ($this->ExtratoSaldo->save($extratoSaldo)) {
                $this->Flash->success(__('The extrato saldo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extrato saldo could not be saved. Please, try again.'));
        }
        $vouchers = $this->ExtratoSaldo->Vouchers->find('list', ['limit' => 200]);
        $this->set(compact('extratoSaldo', 'vouchers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Extrato Saldo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $extratoSaldo = $this->ExtratoSaldo->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $extratoSaldo = $this->ExtratoSaldo->patchEntity($extratoSaldo, $this->request->getData());
            if ($this->ExtratoSaldo->save($extratoSaldo)) {
                $this->Flash->success(__('The extrato saldo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extrato saldo could not be saved. Please, try again.'));
        }
        $vouchers = $this->ExtratoSaldo->Vouchers->find('list', ['limit' => 200]);
        $this->set(compact('extratoSaldo', 'vouchers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Extrato Saldo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $extratoSaldo = $this->ExtratoSaldo->get($id);
        if ($this->ExtratoSaldo->delete($extratoSaldo)) {
            $this->Flash->success(__('The extrato saldo has been deleted.'));
        } else {
            $this->Flash->error(__('The extrato saldo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
