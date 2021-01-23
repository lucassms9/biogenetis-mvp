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
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Encadeamentos');
    }

    public function nextOrder($id_main)
    {
        $encads = $this->Encadeamentos->find('all',[
            'conditions' => ['origem_parent_id' => $id_main],
            'order' => ['ordem' => 'DESC']
        ])->toArray();

        if(!empty($encads)){
            echo json_encode($encads[0]->ordem);
            exit();
        }else{
            echo json_encode(0);
            exit();
        }

    }

    public function saveEncademantoMain($id_main, $regra)
    {
        $retorno = [];
        $origem = $this->Origens->get($id_main);
        $origem->regra_encadeamento = $regra;
        $origem = $this->Origens->save($origem);

        if($origem){
            $retorno = ['error' => 0];
        }else{
            $retorno = ['error' => 1];
        }

        echo json_encode($retorno);
        exit();
    }

    public function allEncads($id)
    {
        $encads = $this->Encadeamentos->find('all',[
            'conditions' => ['origem_parent_id' => $id
        ]])->toArray();

        echo json_encode($encads);
        exit();

    }

    public function removeEncademanto($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $encad = $this->Encadeamentos->get($id);
        if ($this->Encadeamentos->delete($encad)) {
            $retorno = ['error' => 0];
        } else {
            $retorno = ['error' => 1];
        }

        echo json_encode($retorno);
        exit();
    }

    public function saveEncademanto()
    {   
        $retorno = [];
        $req = $this->request->getQuery();
        $newItem = 0;


        $dados_save = [
           'origem_parent_id' => $req['endpoint_parent'],
           'origem_encadeamento_id' => $req['url_encadeamento'],
           'regra' => $req['regra'],
           'ordem' => $req['ordem']
        ];

        if(!empty($req['instancia']) && is_numeric($req['instancia'])){

            $find_encad = $this->Encadeamentos->get($req['instancia']);

            $encad = $this->Encadeamentos->patchEntity($find_encad, $dados_save);
            $encad = $this->Encadeamentos->save($encad);

        }else{
            $newItem = 1;
            $encad = $this->Encadeamentos->newEntity();
            $encad = $this->Encadeamentos->patchEntity($encad, $dados_save);
            $encad = $this->Encadeamentos->save($encad);
        }

        if($encad) {
            if($newItem){
                $encad = $this->Encadeamentos->find()->last();    
            }
            
            $retorno = ['error' => 0, 'encad' => $encad, 'newItem' => $newItem];
        }else{
            $retorno = ['error' => 1, 'encad' => [], 'newItem' => $newItem];
        }

        echo json_encode($retorno);
        exit();

    }
        

    public function origensApi(){
        $endpoints = $this->Origens->find('all')->toArray();
        $combo_endpoint = [];

        foreach ($endpoints as $key => $endpoint) {
            $combo_endpoint[] = ['name' => $endpoint->nome_origem . ' - ' . $endpoint->url_request, 'id' => $endpoint->id];
        }

        echo json_encode($combo_endpoint);
        die();
    }

    public function encadeamentos($id)
    {
        $action = 'Ver Todos';
        $title = 'Endpoints';

        $origen = $this->Origens->get($id, [
            'contain' => ['Encadeamentos'],
        ]);

        if ($this->request->is('post')) {
          
            $encads = [];

            foreach ($this->request->getData('url_encad') as $key => $url_encadid) {
                $encads[$key] = ['origem_encadeamento_id' => $url_encadid];
            }
            foreach ($this->request->getData('regra') as $key => $regraid) {
                $merge_arr = array_merge($encads[$key], ['regra' => $regraid]);
                $encads[$key] = $merge_arr;
            }

            foreach ($this->request->getData('ordem') as $key => $ordemid) {
                $merge_arr = array_merge($encads[$key], ['ordem' => $ordemid]);
                $encads[$key] = $merge_arr;
            }

            $encads_find = $this->Encadeamentos->find('all',[
                'conditions' => ['origem_parent_id' => $id
            ]])->toArray();

            foreach ($encads_find as $key => $encad_find) {
               $this->Encadeamentos->delete($encad_find);
            }

            foreach ($encads as $key => $encad) {
                if(!empty($encad['origem_encadeamento_id']) && !empty($encad['regra']) && !empty($encad['ordem'])){
                    $encad['origem_parent_id'] = $id;
                    $new_encad = $this->Encadeamentos->newEntity();
                    $new_encad = $this->Encadeamentos->patchEntity($new_encad, $encad);
                    $new_encad = $this->Encadeamentos->save($new_encad);
                }
            }
            

            $endpoint_main = $this->Origens->get($id);
            $endpoint_main->regra_encadeamento = $this->request->getData('regra_main');

            $this->Origens->save($endpoint_main);

            $this->Flash->success(__('Dados salvos com sucesso.'));

            return $this->redirect(['action' => 'index']);
           

        }

        $endpoints = $this->Origens->find('all')->toArray();
        $combo_endpoint = [];

        $regras = [
            'Positivo' => 'Positivo',
            'Negativo' => 'Negativo',
            'Inadequado' => 'Inadequado',
            'Restante' => 'Restante'
        ];

        foreach ($endpoints as $key => $endpoint) {
            $combo_endpoint[$endpoint->id] = $endpoint->nome_origem . ' - ' . $endpoint->url_request;
        }


        $encadeamento = $this->Encadeamentos->newEntity();
        $nextOrder = count($origen->encadeamentos) + 1;

        $this->set(compact('origen', 'action', 'title','encadeamento','combo_endpoint','regras','nextOrder'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        $action = 'Ver Todos';
        $title = 'Endpoints';

        $origens = $this->paginate($this->Origens,[
            'contain' => ['Encadeamentos.Origens'],
            'limit' => 25,
        ]);

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
