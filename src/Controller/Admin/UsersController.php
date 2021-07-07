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

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('TecnicoPeritos');
        $this->loadComponent('Helpers');
        $this->loadComponent('RNDS');
    }


    public function termoDeUso()
    {
    }

    public function teste()
    {
        $action = 'Home';
        $title = 'Biogenetics';

        $this->RNDS->getToken();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function home()
    {
        $action = 'Home';
        $title = 'Biogenetics';

        $this->set(compact('action', 'title'));
    }

    public function index()
    {

        $action = 'Ver Todos';
        $title = 'Usuários';

        $conditions = [];

        $this->paginate = [
            'contain' => ['Clientes', 'UserTypes'],
        ];

        if ($this->Auth->user('user_type_id') == 2) {
            $conditions['cliente_id'] = $this->Auth->user('cliente_id');
        }


        $users = $this->paginate($this->Users, [
            'conditions' => $conditions
        ]);

        $showActions = true;
        $this->set(compact('users', 'action', 'title', 'showActions'));
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
        $action = 'Cadastrar';
        $title = 'Usuários';

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $req = $this->request->getData();

            if (!empty($req['foto_assinatura_digital'])) {
                $url = 'certificados/' . $req['foto_assinatura_digital']['name'];
                move_uploaded_file($req['foto_assinatura_digital']['tmp_name'], CERTIFICADOS . $req['foto_assinatura_digital']['name']);
                $req['foto_assinatura_digital'] = $url;
            }

            $user = $this->Users->patchEntity($user, $req);

            $user = $this->Users->save($user);
            if ($user) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $conditionsType = [];
        $conditionsCliente = [];


        if ($this->Auth->user('user_type_id') == 2) {
            $conditionsType['UserTypes.id in'] = ['2', '3', '4', '5', '6'];
        }

        if ($this->Auth->user('user_type_id') == 2) {
            $conditionsCliente['Clientes.id'] = $this->Auth->user('cliente_id');
        }

        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200, 'conditions' => $conditionsType]);

        $clientes = $this->Users->Clientes->find('list', ['limit' => 200, 'conditions' => $conditionsCliente]);

        $this->set(compact('user', 'userTypes', 'clientes', 'action', 'title'));
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
        $action = 'Editar Dados';
        $title = 'Usuários';

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $conditionsType = [];
        $conditionsCliente = [];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();

            if (!empty($request['foto_assinatura_digital'])) {
                $url = 'certificados/' . $request['foto_assinatura_digital']['name'];
                move_uploaded_file($request['foto_assinatura_digital']['tmp_name'], CERTIFICADOS . $request['foto_assinatura_digital']['name']);
                $request['foto_assinatura_digital'] = $url;
            }

            if (isset($request['senha']) && empty($request['senha'])) {
                unset($request['senha']);
            }

            $request['cpf'] = $this->Helpers->stringToNumber($request['cpf']);
            $user = $this->Users->patchEntity($user, $request);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        if ($this->Auth->user('user_type_id') == 2) {
            $conditionsType['UserTypes.id in'] = ['2', '3'];
        }

        if ($this->Auth->user('user_type_id') == 2) {
            $conditionsCliente['Clientes.id'] = $this->Auth->user('cliente_id');
        }

        $userTypes = $this->Users->UserTypes->find('list', ['limit' => 200, 'conditions' => $conditionsType]);

        $clientes = $this->Users->Clientes->find('list', ['limit' => 200, 'conditions' => $conditionsCliente]);
        $this->set(compact('user', 'userTypes', 'clientes', 'action', 'title'));
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

        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        if ($this->request->is('post')) {

            $unidades = array();

            $user = $this->Users->find('all', [
                'contain' => ['Clientes'],
                'conditions' => [
                    'email' => $this->request->getData('email'),
                    'senha' => md5($this->request->getData('password')),

                ]
            ])->first();
            $permissoes = array(
                'tecnico' => false,
                'manager' => false,
                'adm' => false,
            );

            if (!empty($user)) {
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
                    default:
                        break;
                }

                $user['permissoes'] = $permissoes;

                $this->Auth->setUser($user);

                if ($user->user_type_id == 3) {
                    // return $this->redirect(['controller' => 'amostras', 'action' => 'index']);
                }

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('E-mail ou senha incorretos'));
            }
            // $this->request->getData('email') = $this->request->getData('email');
        }

        $this->set(compact('user'));

        $this->viewBuilder()->setLayout('login');
    }
}
