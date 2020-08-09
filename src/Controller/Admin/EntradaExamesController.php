<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * EntradaExames Controller
 *
 * @property \App\Model\Table\EntradaExamesTable $EntradaExames
 *
 * @method \App\Model\Entity\EntradaExame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EntradaExamesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->exame_tipos = [
            'SALIVA' => 'SALIVA',
            'SWAB' => 'SWAB'
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
        $title = 'Pré Exame';

        $entradaExames = $this->paginate($this->EntradaExames);

        $this->set(compact('entradaExames', 'action','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Entrada Exame id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $entradaExame = $this->EntradaExames->get($id, [
            'contain' => ['Exames'],
        ]);

        $this->set('entradaExame', $entradaExame);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Pré Exame';

        $entradaExame = $this->EntradaExames->newEntity();
        if ($this->request->is('post')) {
            $entradaExame = $this->EntradaExames->patchEntity($entradaExame, $this->request->getData());
            if ($this->EntradaExames->save($entradaExame)) {
                $this->Flash->success(__('The entrada exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The entrada exame could not be saved. Please, try again.'));
        }

        $exame_tipos = $this->exame_tipos;

        $this->set(compact('entradaExame','action', 'title','exame_tipos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Entrada Exame id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Pré Exame';

        $entradaExame = $this->EntradaExames->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $entradaExame = $this->EntradaExames->patchEntity($entradaExame, $this->request->getData());
            if ($this->EntradaExames->save($entradaExame)) {
                $this->Flash->success(__('The entrada exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The entrada exame could not be saved. Please, try again.'));
        }

        $exame_tipos = $this->exame_tipos;

        $this->set(compact('entradaExame','action','title','exame_tipos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Entrada Exame id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $entradaExame = $this->EntradaExames->get($id);
        if ($this->EntradaExames->delete($entradaExame)) {
            $this->Flash->success(__('The entrada exame has been deleted.'));
        } else {
            $this->Flash->error(__('The entrada exame could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
