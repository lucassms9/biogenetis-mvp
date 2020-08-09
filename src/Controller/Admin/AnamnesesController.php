<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Anamneses Controller
 *
 * @property \App\Model\Table\AnamnesesTable $Anamneses
 *
 * @method \App\Model\Entity\Anamnese[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnamnesesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pacientes'],
        ];
        $anamneses = $this->paginate($this->Anamneses);

        $this->set(compact('anamneses'));
    }

    /**
     * View method
     *
     * @param string|null $id Anamnese id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $anamnese = $this->Anamneses->get($id, [
            'contain' => ['Pacientes'],
        ]);

        $this->set('anamnese', $anamnese);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $anamnese = $this->Anamneses->newEntity();
        if ($this->request->is('post')) {
            $anamnese = $this->Anamneses->patchEntity($anamnese, $this->request->getData());
            if ($this->Anamneses->save($anamnese)) {
                $this->Flash->success(__('The anamnese has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The anamnese could not be saved. Please, try again.'));
        }
        $pacientes = $this->Anamneses->Pacientes->find('list', ['limit' => 200]);
        $this->set(compact('anamnese', 'pacientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Anamnese id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $anamnese = $this->Anamneses->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $anamnese = $this->Anamneses->patchEntity($anamnese, $this->request->getData());
            if ($this->Anamneses->save($anamnese)) {
                $this->Flash->success(__('The anamnese has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The anamnese could not be saved. Please, try again.'));
        }
        $pacientes = $this->Anamneses->Pacientes->find('list', ['limit' => 200]);
        $this->set(compact('anamnese', 'pacientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Anamnese id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $anamnese = $this->Anamneses->get($id);
        if ($this->Anamneses->delete($anamnese)) {
            $this->Flash->success(__('The anamnese has been deleted.'));
        } else {
            $this->Flash->error(__('The anamnese could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
