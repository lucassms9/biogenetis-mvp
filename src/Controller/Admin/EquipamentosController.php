<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Equipamentos Controller
 *
 * @property \App\Model\Table\EquipamentosTable $Equipamentos
 *
 * @method \App\Model\Entity\Equipamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipamentosController extends AppController
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
        $title = 'Equipamentos';

        $this->paginate = [
            'contain' => ['Croquis'],
        ];
        $equipamentos = $this->paginate($this->Equipamentos);

        $this->set(compact('equipamentos','action', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Equipamento id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $equipamento = $this->Equipamentos->get($id, [
            'contain' => ['Croquis'],
        ]);

        $this->set('equipamento', $equipamento);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Equipamentos';

        $equipamento = $this->Equipamentos->newEntity();
        if ($this->request->is('post')) {
            $equipamento = $this->Equipamentos->patchEntity($equipamento, $this->request->getData());
            if ($this->Equipamentos->save($equipamento)) {
                $this->Flash->success(__('The equipamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipamento could not be saved. Please, try again.'));
        }
        $croquis = $this->Equipamentos->Croquis->find('list', ['limit' => 200]);
        $exame_tipos = $this->exame_tipos;
        $this->set(compact('equipamento', 'croquis','exame_tipos', 'action','title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Equipamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Equipamentos';

        $equipamento = $this->Equipamentos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $equipamento = $this->Equipamentos->patchEntity($equipamento, $this->request->getData());
            if ($this->Equipamentos->save($equipamento)) {
                $this->Flash->success(__('The equipamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipamento could not be saved. Please, try again.'));
        }
        $croquis = $this->Equipamentos->Croquis->find('list', ['limit' => 200]);

        $exame_tipos = $this->exame_tipos;

        $this->set(compact('equipamento', 'croquis','exame_tipos','title', 'action'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $equipamento = $this->Equipamentos->get($id);
        if ($this->Equipamentos->delete($equipamento)) {
            $this->Flash->success(__('The equipamento has been deleted.'));
        } else {
            $this->Flash->error(__('The equipamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
