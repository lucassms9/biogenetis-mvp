<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
/**
 * Exames Controller
 *
 * @property \App\Model\Table\ExamesTable $Exames
 *
 * @method \App\Model\Entity\Exame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExamesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pacientes', 'Amostras'],
        ];
        $exames = $this->paginate($this->Exames);

        $this->set(compact('exames'));
    }

    /**
     * View method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => ['Pacientes', 'Amostras'],
        ]);

        $this->set('exame', $exame);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exame = $this->Exames->newEntity();
        if ($this->request->is('post')) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $pacientes = $this->Exames->Pacientes->find('list', ['limit' => 200]);
        $amostras = $this->Exames->Amostras->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'pacientes', 'amostras'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $pacientes = $this->Exames->Pacientes->find('list', ['limit' => 200]);
        $amostras = $this->Exames->Amostras->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'pacientes', 'amostras'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exame = $this->Exames->get($id);
        if ($this->Exames->delete($exame)) {
            $this->Flash->success(__('The exame has been deleted.'));
        } else {
            $this->Flash->error(__('The exame could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
