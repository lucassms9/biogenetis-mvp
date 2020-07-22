<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Origens Controller
 *
 * @property \App\Model\Table\OrigensTable $Origens
 *
 * @method \App\Model\Entity\Origen[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrigensController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        $action = 'Ver Todos';
        $title = 'Endpoints';

        $origens = $this->paginate($this->Origens);

        $this->set(compact('origens', 'action', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Origen id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $origen = $this->Origens->get($id, [
            'contain' => [],
        ]);

        $this->set('origen', $origen);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {      
        $action = 'Cadastrar';
        $title = 'Endpoints';

        $amostra_tipos = [
            'SWAB' => 'SWAB',
            'SALIVA' => 'SALIVA'
        ];

        $equip_tipos = [
            'LCMS' => 'LCMS',
            'FTIR' => 'FTIR'
        ];
        $iAModelTypes = [
            'ML' => 'ML',
            'DL' => 'DL'
        ];

        $origen = $this->Origens->newEntity();
        if ($this->request->is('post')) {
            $origen = $this->Origens->patchEntity($origen, $this->request->getData());
            if ($this->Origens->save($origen)) {
                $this->Flash->success(__('Endpoint salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The origen could not be saved. Please, try again.'));
        }
        $this->set(compact('origen','action','title','amostra_tipos','equip_tipos','iAModelTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Origen id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {   
        $action = 'Editar Dados';
        $title = 'Endpoints';
        
        $amostra_tipos = [
            'SWAB' => 'SWAB',
            'SALIVA' => 'SALIVA'
        ];

        $equip_tipos = [
            'LCMS' => 'LCMS',
            'FTIR' => 'FTIR'
        ];
        $iAModelTypes = [
            'ML' => 'ML',
            'DL' => 'DL'
        ];

        $origen = $this->Origens->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $origen = $this->Origens->patchEntity($origen, $this->request->getData());

            $origen = $this->Origens->save($origen);
            if ($origen) {
                $this->Flash->success(__('Endpoint salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The origen could not be saved. Please, try again.'));
        }
        $this->set(compact('origen','action','title','amostra_tipos','equip_tipos','iAModelTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Origen id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $origen = $this->Origens->get($id);
        if ($this->Origens->delete($origen)) {
            $this->Flash->success(__('The origen has been deleted.'));
        } else {
            $this->Flash->error(__('The origen could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
