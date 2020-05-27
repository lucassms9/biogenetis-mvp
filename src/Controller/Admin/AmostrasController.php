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
        $this->loadModel('Exames');
    }



    public function sendData()
    {
        if ($this->request->is('post')) {

            for ($i = 0; $i < $this->request->data['totalFiles']; $i++) {
                 $amostra = [
                    'amostra_id' => $this->request->data['amostraid'.$i.'_'],
                    'uf' => $this->request->data['uf'.$i],
                    'sexo' => $this->request->data['sexo'.$i],
                    'idade' => $this->request->data['idade'.$i],
                ];

                $amostra_save = $this->Amostras->newEntity();
                $amostra_save = $this->Amostras->patchEntity($amostra_save,[
                    'code_amostra' => $amostra['amostra_id'],
                    'uf' => $amostra['uf'],
                    'idade' => $amostra['idade'],
                    'sexo' => $amostra['sexo'],
                ]);
                $amostra_save = $this->Amostras->save($amostra_save);

                // FAZ COMUNICAO COM O SERVICO DE IA
                // SALVA O RETORNO EM RESULTADO

                $exame_find = $this->Exames->find('all',[
                    'conditions' => ['amostra_id' => $amostra['amostra_id']]
                ])->first();

                $exame_find->resultado = 'Em Análise';
                $this->Exames->save($exame_find);
                
            }
            if($this->request->data['totalFiles'] == 1){
                return $this->redirect(['constroller' => 'relatorio', 'action' => 'index']);
            }else{
                return $this->redirect(['action' => 'index']);
            }
              
        }
    }

    public function import()
    {
         if ($this->request->is('post')) {

            try {   
                
                if(!empty($this->request->data['file'])){
                    $file = $this->request->data['file'];

                    if($file['size'] > 0){

                        move_uploaded_file($file['tmp_name'], AMOSTRAS . $file['name']);

                        $amostra_id = explode('.', $file['name']);
                        $exame = [
                            'amostra_id' => $amostra_id[0],
                            'file_name' => $file['name'],
                            'created_by' => $this->Auth->user('id'),
                        ];

                        $exame_save = $this->Exames->newEntity();
                        $exame_save = $this->Exames->patchEntity($exame_save, $exame);
                        $exame_save = $this->Exames->save($exame_save);

                        if($exame_save){
                            echo json_encode($exame_save);
                            exit;
                        }else{
                            throw new Exception("Error Processing Request", 1);
                        }

                    }
                      
                }

             } catch (Exception $e) {
                  $this->Flash->error(__('Tivemos algum problema ao enviar os Exames'));
            }
         }
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
