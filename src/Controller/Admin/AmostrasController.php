<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Amostras Controller
 *
 * @property \App\Model\Table\AmostrasTable $Amostras
 *
 * @method \App\Model\Entity\Amostra[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AmostrasController extends AppController
{   

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   

        $limitDefault = 6;
        $options = [];

        $this->paginate = [
            'limit' => $limitDefault
        ];

        $amostras = $this->paginate($this->Amostras);

        $this->set(compact('amostras'));
    }

    /**
     * View method
     *
     * @param string|null $id Amostra id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $amostra = $this->Amostras->get($id, [
            'contain' => ['Exames'],
        ]);

        $this->set('amostra', $amostra);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $amostra = $this->Amostras->newEntity();
        if ($this->request->is('post')) {
            $amostra = $this->Amostras->patchEntity($amostra, $this->request->getData());
            if ($this->Amostras->save($amostra)) {
                $this->Flash->success(__('The amostra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The amostra could not be saved. Please, try again.'));
        }
        $this->set(compact('amostra'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Amostra id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $amostra = $this->Amostras->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $amostra = $this->Amostras->patchEntity($amostra, $this->request->getData());
            if ($this->Amostras->save($amostra)) {
                $this->Flash->success(__('The amostra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The amostra could not be saved. Please, try again.'));
        }
        $this->set(compact('amostra'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Amostra id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $amostra = $this->Amostras->get($id);
        if ($this->Amostras->delete($amostra)) {
            $this->Flash->success(__('The amostra has been deleted.'));
        } else {
            $this->Flash->error(__('The amostra could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
