<?php
namespace App\Controller\Admin;

use App\Controller\AppController;


/**
 * Croquis Controller
 *
 * @property \App\Model\Table\CroquisTable $Croquis
 *
 * @method \App\Model\Entity\Croqui[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CroquisController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->exame_tipos = [
            'SALIVA' => 'SALIVA',
            'SWAB' => 'SWAB'
        ];

        $this->equipamento_tipos = [
            'LCMS' => 'LCMS',
            'FTIR' => 'FTIR'
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
        $title = 'Croquis';

        $croquis = $this->paginate($this->Croquis);

        $this->set(compact('croquis','action','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $croqui = $this->Croquis->get($id, [
            'contain' => ['Equipamentos'],
        ]);

        $this->set('croqui', $croqui);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $action = 'Cadastrar';
        $title = 'Croquis';

        $croqui = $this->Croquis->newEntity();
        if ($this->request->is('post')) {
            $croqui = $this->Croquis->patchEntity($croqui, $this->request->getData());
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }

        $equipamento_tipos = $this->equipamento_tipos;
        $exame_tipos = $this->exame_tipos;

        $this->set(compact('croqui','action','title','equipamento_tipos','exame_tipos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $action = 'Editar';
        $title = 'Croquis';

        $croqui = $this->Croquis->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $croqui = $this->Croquis->patchEntity($croqui, $this->request->getData());
            if ($this->Croquis->save($croqui)) {
                $this->Flash->success(__('The croqui has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The croqui could not be saved. Please, try again.'));
        }
        $equipamento_tipos = $this->equipamento_tipos;
        $exame_tipos = $this->exame_tipos;

        $this->set(compact('croqui','action','title','equipamento_tipos','exame_tipos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Croqui id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $croqui = $this->Croquis->get($id);
        if ($this->Croquis->delete($croqui)) {
            $this->Flash->success(__('The croqui has been deleted.'));
        } else {
            $this->Flash->error(__('The croqui could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
