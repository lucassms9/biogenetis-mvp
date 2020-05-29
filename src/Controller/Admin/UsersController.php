<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function home(){

    }
    
    public function index()
    {   

        $conditions = [];

        $this->paginate = [
            'contain' => ['Clientes','UserTypes'],
        ];


        if($this->Auth->user('user_type_id') == 2){
            $conditions['cliente_id'] = $this->Auth->user('cliente_id');
        }
        

        $users = $this->paginate($this->Users,[
            'conditions' => $conditions
        ]);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['UserTypes', 'Clientes'],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $conditionsType = [];
        $conditionsCliente = [];


        if($this->Auth->user('user_type_id') == 2){
            $conditionsType['UserTypes.id in'] = ['2','3'];
        } 

        if($this->Auth->user('user_type_id') == 2){
            $conditionsCliente['Clientes.id'] = $this->Auth->user('cliente_id');
        }

        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200,'conditions' => $conditionsType]);

        $clientes = $this->Users->Clientes->find('list', ['limit' => 200, 'conditions' => $conditionsCliente]);
        $this->set(compact('user', 'userTypes', 'clientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
         if($this->Auth->user('user_type_id') == 2){
            $conditionsType['UserTypes.id in'] = ['2','3'];
        } 

        if($this->Auth->user('user_type_id') == 2){
            $conditionsCliente['Clientes.id'] = $this->Auth->user('cliente_id');
        }

        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200,'conditions' => $conditionsType]);

        $clientes = $this->Users->Clientes->find('list', ['limit' => 200, 'conditions' => $conditionsCliente]);
        $this->set(compact('user', 'userTypes', 'clientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function login()
    {

        $user = $this->Users->newEntity();
        
        if( $this->Auth->user() ){
             return $this->redirect( $this->Auth->redirectUrl() );
        }

        if ($this->request->is('post')) {

            $unidades = array();
            
            $user = $this->Users->find('all',[
                'conditions' => [
                    'email' => $this->request->data['email'],
                    'senha' => md5($this->request->data['password']),

                ]
            ])->first();
            $permissoes = array('tecnico' => false,
                                    'manager' => false,
                                    'adm' => false,
                                    );


            if(!empty($user)){
                    switch ($user->user_type_id) {
                        case 3:
                            $permissoes['tecnico'] = true;
                            break;
                        case 2:
                            $permissoes['manager'] = true;
                            break;
                        case 1:
                            $permissoes['adm'] =  $permissoes['tecnico'] = $permissoes['manager'] = true;   
                            break;
                        default: break;
                    }

                $user['permissoes'] = $permissoes;

                $this->Auth->setUser($user);
                
                if($user->user_type_id == 3){
                    return $this->redirect(['controller' =>'amostras', 'action' => 'index']);
                }

                return $this->redirect($this->Auth->redirectUrl());
            }else{
                $this->Flash->error(__('E-mail ou senha incorretos'));
            }
            $this->request->data['email'] = $this->request->data['email'];
        }

        $this->set(compact('user'));
        
        $this->viewBuilder()->setLayout('login');

    }

}
