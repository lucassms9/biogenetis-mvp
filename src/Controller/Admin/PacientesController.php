<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Pacientes Controller
 *
 * @property \App\Model\Table\PacientesTable $Pacientes
 *
 * @method \App\Model\Entity\Paciente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PacientesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->sexos = [
            'M' => 'M',
            'F' => 'F'
        ];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {

        $action = 'Ver Todos';
        $title = 'Pacientes';

        $pacientes = $this->paginate($this->Pacientes);

        $this->set(compact('pacientes','action','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $paciente = $this->Pacientes->get($id, [
            'contain' => ['Exames'],
        ]);

        $this->set('paciente', $paciente);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Pacientes';

        $paciente = $this->Pacientes->newEntity();
        if ($this->request->is('post')) {
            $paciente = $this->Pacientes->patchEntity($paciente, $this->request->getData());
            if ($this->Pacientes->save($paciente)) {
                $this->Flash->success(__('The paciente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The paciente could not be saved. Please, try again.'));
        }
        $sexos = $this->sexos;

        $this->set(compact('paciente','action','title','sexos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Clientes';

        $paciente = $this->Pacientes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paciente = $this->Pacientes->patchEntity($paciente, $this->request->getData());
            if ($this->Pacientes->save($paciente)) {
                $this->Flash->success(__('The paciente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The paciente could not be saved. Please, try again.'));
        }
        $sexos = $this->sexos;

        $this->set(compact('paciente','action','title','sexos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $paciente = $this->Pacientes->get($id);
        if ($this->Pacientes->delete($paciente)) {
            $this->Flash->success(__('The paciente has been deleted.'));
        } else {
            $this->Flash->error(__('The paciente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
