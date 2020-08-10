<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * TecnicoPeritos Controller
 *
 * @property \App\Model\Table\TecnicoPeritosTable $TecnicoPeritos
 *
 * @method \App\Model\Entity\TecnicoPerito[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TecnicoPeritosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $action = 'Ver Todos';
        $title = 'Peritos Técnicos';

        $tecnicoPeritos = $this->paginate($this->TecnicoPeritos);

        $this->set(compact('tecnicoPeritos','action','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Tecnico Perito id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tecnicoPerito = $this->TecnicoPeritos->get($id, [
            'contain' => [],
        ]);

        $this->set('tecnicoPerito', $tecnicoPerito);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Peritos Técnicos';

        $tecnicoPerito = $this->TecnicoPeritos->newEntity();
        if ($this->request->is('post')) {
            $tecnicoPerito = $this->TecnicoPeritos->patchEntity($tecnicoPerito, $this->request->getData());
            if ($this->TecnicoPeritos->save($tecnicoPerito)) {
                $this->Flash->success(__('The tecnico perito has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tecnico perito could not be saved. Please, try again.'));
        }
        $this->set(compact('tecnicoPerito','action','title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Tecnico Perito id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar Dados';
        $title = 'Peritos Técnicos';

        $tecnicoPerito = $this->TecnicoPeritos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tecnicoPerito = $this->TecnicoPeritos->patchEntity($tecnicoPerito, $this->request->getData());
            if ($this->TecnicoPeritos->save($tecnicoPerito)) {
                $this->Flash->success(__('The tecnico perito has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The tecnico perito could not be saved. Please, try again.'));
        }
        $this->set(compact('tecnicoPerito','action','title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Tecnico Perito id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tecnicoPerito = $this->TecnicoPeritos->get($id);
        if ($this->TecnicoPeritos->delete($tecnicoPerito)) {
            $this->Flash->success(__('The tecnico perito has been deleted.'));
        } else {
            $this->Flash->error(__('The tecnico perito could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
