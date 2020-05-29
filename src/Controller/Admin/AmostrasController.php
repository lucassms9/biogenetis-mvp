<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Http\Client;
use DOMDocument;
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

            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 0);
            set_time_limit(0);

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
                    'sexo' => strtoupper($amostra['sexo']),
                ]);
                $amostra_save = $this->Amostras->save($amostra_save);

                // FAZ COMUNICAO COM O SERVICO DE IA
                // SALVA O RETORNO EM RESULTADO

                $exame_find = $this->Exames->find('all',[
                    'conditions' => ['amostra_id' => $amostra['amostra_id']]
                ])->first();

                $integration = $this->callIntegration($exame_find);
                
                if($integration == 'Positive'){
                    $integration = 'Positivo';
                }else if($integration == 'Negative'){
                     $integration = 'Negativo';
                }else{
                    $integration = 'Inconclusivo';
                }

                $exame_find->resultado = $integration;
                $this->Exames->save($exame_find);
                
            }
            if($this->request->data['totalFiles'] == 1){
                return $this->redirect(['constroller' => 'relatorio', 'action' => 'index']);
            }else{
                return $this->redirect(['action' => 'index']);
            }
              
        }
    }
    
    public function callIntegration($exame)
    {
        
        $url = "http://ec2-177-71-237-9.sa-east-1.compute.amazonaws.com/covid19/sampletest.php";
        $filedata = AMOSTRAS . $exame->amostra_id. '.csv';

        $http = new Client();
        $response = $http->post($url, [
          'Userfile' => fopen($filedata, 'r'),
        ]);

        $result = $this->html_to_obj($response->body);
        return $result;

    }

    public function html_to_obj($html) {
        try {

            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $getElement = $this->element_to_obj($dom->documentElement);
            $result = trim($getElement['children'][0]['children'][1]['html']);
            $hanldeData = substr($result, 0, 8);
            return $hanldeData;
        } catch (Exception $e) {
            throw new Exception($e->message, 1);
        }
    }

    public function element_to_obj($element)
    {
        $obj = array( "tag" => $element->tagName );
        foreach ($element->attributes as $attribute) {
            $obj[$attribute->name] = $attribute->value;
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj["html"] = $subElement->wholeText;
            }
            else {
                $obj["children"][] = $this->element_to_obj($subElement);
            }
        }
        return $obj;

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

        $limitDefault = 15;
        $conditions = [];

        $this->paginate = [
            'limit' => $limitDefault,
            'contain' => ['Exames']
        ];

        if(!empty($this->request->query['amostra_id'])){
            $conditions['code_amostra like'] = $this->request->query['amostra_id'].'%';
        }

        $amostras = $this->paginate($this->Amostras,[
            'conditions' => $conditions
        ]);

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
