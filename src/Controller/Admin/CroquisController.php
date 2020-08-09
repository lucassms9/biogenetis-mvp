<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Croquis Controller
 *
 * @property \App\Model\Table\CroquisTable $Croquis
 *
 * @method \App\Model\Entity\Croqui[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CroquisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $croquis = $this->paginate($this->Croquis);

        $this->set(compact('croquis'));
    }

    /**
     * View method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $croqui = $this->Croquis->get($id, [
            'contain' => ['Equipamentos'],
        ]);

        $this->set('croqui', $croqui);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $croqui = $this->Croquis->newEntity();
        if ($this->request->is('post')) {
            $croqui = $this->Croquis->patchEntity($croqui, $this->request->getData());
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }
        $this->set(compact('croqui'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $croqui = $this->Croquis->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $croqui = $this->Croquis->patchEntity($croqui, $this->request->getData());
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }
        $this->set(compact('croqui'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $croqui = $this->Croquis->get($id);
        if ($this->Croquis->delete($croqui)) {
            $this->Flash->success(__('The croqui has been deleted.'));
        } else {
            $this->Flash->error(__('The croqui could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
