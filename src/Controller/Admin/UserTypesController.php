<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * UserTypes Controller
 *
 * @property \App\Model\Table\UserTypesTable $UserTypes
 *
 * @method \App\Model\Entity\UserType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $userTypes = $this->paginate($this->UserTypes);

        $this->set(compact('userTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id User Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userType = $this->UserTypes->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('userType', $userType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userType = $this->UserTypes->newEntity();
        if ($this->request->is('post')) {
            $userType = $this->UserTypes->patchEntity($userType, $this->request->getData());
            if ($this->UserTypes->save($userType)) {
                $this->Flash->success(__('The user type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user type could not be saved. Please, try again.'));
        }
        $this->set(compact('userType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userType = $this->UserTypes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userType = $this->UserTypes->patchEntity($userType, $this->request->getData());
            if ($this->UserTypes->save($userType)) {
                $this->Flash->success(__('The user type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user type could not be saved. Please, try again.'));
        }
        $this->set(compact('userType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userType = $this->UserTypes->get($id);
        if ($this->UserTypes->delete($userType)) {
            $this->Flash->success(__('The user type has been deleted.'));
        } else {
            $this->Flash->error(__('The user type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
