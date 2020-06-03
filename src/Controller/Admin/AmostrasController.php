<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Http\Client;
use DOMDocument;
use Cake\Http\Exception\BadRequestException;
use PHPExcel;
use PHPExcel_IOFactory;
use Cake\I18n\Time;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;

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
        $this->loadComponent('Email');
    }

    public function sendEmail()
    {
         if ($this->request->is('post')) {
            $conditions = [];

            $redirect = [
                'amostra_id' => $this->request->data['amostra_id'],
                'lote' => $this->request->data['lote'],
                'data_init' => $this->request->data['data_init'],
                'data_fim' => $this->request->data['data_fim'],
            ];

            if($this->Auth->user('user_type_id') == 3){
                $conditions['Exames.created_by'] = $this->Auth->user('id');
            }

            if($this->Auth->user('user_type_id') == 2){
                $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
            }
            
            if(!empty($this->request->data['amostra_id'])){
                $conditions['code_amostra'] = $this->request->data['amostra_id'];
            } 

            if(!empty($this->request->data['lote'])){
                $conditions['lote'] = $this->request->data['lote'];
            }

            if (!empty($this->request->data['data_init'])){
                $data_de = $this->request->data['data_init'];
                $conditions['cast(Exames.created as date) >='] = $data_de;
            }

            if (!empty($this->request->data['data_fim'])){
                $data_ate = $this->request->data['data_fim'];
                $conditions['cast(Exames.created as date) >='] = $data_ate;
             }

            $amostras = $this->Amostras->find('all',[
                'contain' => 'Exames.Users',
                'conditions' => $conditions
            ])->toList();

            $qtd_colunas = 7;

            $nome_colunas = [
                'Id',
                'Amostra ID',
                'Lote',
                'UF',
                'Idade',
                'Sexo',
                'Resultado',
                'Data de criação',
            ];

            $alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');

            $objPHPExcel = new PHPExcel();

            for ($i = 0; $i <= $qtd_colunas; $i++)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$i] . '1', $nome_colunas[$i]); 

            foreach($amostras as $i => $amostra){
                 $dados = [
                    $amostra->id,
                    $amostra->code_amostra,
                    $amostra->lote,
                    $amostra->uf,
                    $amostra->idade,
                    $amostra->sexo,
                    $amostra->exame->resultado,
                    $amostra->created->i18nFormat('dd/MM/yyyy HH:mm')
                ];

                for ($j = 0; $j <= $qtd_colunas; $j++) {
                    if (isset($alfabeto[$j])) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2),$dados[$j]);
                    }
                }

            }

            $nome_arquivo = md5(date('Y-m-d_H-i-s')) . '_reimport.xls';

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save(XLS_AMOSTRAS . $nome_arquivo);


            $dadosEmail = array();
            $dadosEmail['from'] = ['contato@testecovidexpress.com.br' => 'Covid Express'];
            $dadosEmail['to'] = $this->Auth->user('email');
            // $dadosEmail['cc'] = 'lucas.santos@dedtechsolutions.com.br';
            $dadosEmail['subject'] = 'Relatório - Amostras';

            $dadosEmail['message'] = 'segue em anexo o relatório das amostras';

            $dadosEmail['attachments'] = [
                                            $nome_arquivo => [
                                                'file' => XLS_AMOSTRAS . $nome_arquivo,
                                                // 'mimetype' => 'image/png',
                                                // 'contentId' => 'my-unique-id'
                                            ]
                                        ];

           
            $this->Email->sendEmail($dadosEmail);
            
            $handleRed = array_merge(['controller' => 'amostras', 'action' => 'index'], $redirect);

            $this->Flash->success(__('E-mail enviado com sucesso!'));
           return $this->redirect($handleRed);
            
        }
    }

    public function generateExcel()
    {   

        if ($this->request->is('post')) {
            $conditions = [];

            if($this->Auth->user('user_type_id') == 3){
                $conditions['Exames.created_by'] = $this->Auth->user('id');
            }

            if($this->Auth->user('user_type_id') == 2){
                $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
            }
            
            if(!empty($this->request->data['amostra_id'])){
                $conditions['code_amostra'] = $this->request->data['amostra_id'];
            } 

            if(!empty($this->request->data['lote'])){
                $conditions['lote'] = $this->request->data['lote'];
            }

            if (!empty($this->request->data['data_init'])){
                $data_de = $this->request->data['data_init'];
                $conditions['cast(Exames.created as date) >='] = $data_de;
            }

            if (!empty($this->request->data['data_fim'])){
                $data_ate = $this->request->data['data_fim'];
                $conditions['cast(Exames.created as date) >='] = $data_ate;
             }

            $amostras = $this->Amostras->find('all',[
                'contain' => 'Exames.Users',
                'conditions' => $conditions
            ])->toList();

            $qtd_colunas = 7;

            $nome_colunas = [
                'Id',
                'Amostra ID',
                'Data de criação',
                'Lote',
                'UF',
                'Idade',
                'Sexo',
                'Resultado',
            ];

            $alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');

            $objPHPExcel = new PHPExcel();

            for ($i = 0; $i <= $qtd_colunas; $i++)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$i] . '1', $nome_colunas[$i]); 

            foreach($amostras as $i => $amostra){
                 $dados = [
                    $amostra->id,
                    $amostra->code_amostra,
                    $amostra->created->i18nFormat('dd/MM/yyyy HH:mm'),
                    $amostra->lote,
                    $amostra->uf,
                    $amostra->idade,
                    $amostra->sexo,
                    $amostra->exame->resultado,
                ];

                for ($j = 0; $j <= $qtd_colunas; $j++) {
                    if (isset($alfabeto[$j])) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2),$dados[$j]);
                    }
                }

            }

            $arquivo = 'amostras_'.date('Y-m-d-H-i-s'); 

            // Redirect output to a client’s web browser (Excel5) 
            header("Content-Type: application/vnd.ms-excel"); 
            header("Content-Disposition: attachment;filename=$arquivo.xls"); 
            header("Cache-Control: max-age=0"); 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
            $objWriter->save('php://output');

        }

    }

    public function sendData()
    {
        if ($this->request->is('post')) {

            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $date_init = date('YmdHi');
            $amostras = [];

            $itemFor = $this->request->data['totalFiles'] - $this->request->data['filesRemoved'];


            foreach ($this->request->data['amostraid'] as $key => $amostraid) {
                $amostras[$key] = ['amostra_id' => $amostraid];
            } 
            foreach ($this->request->data['uf'] as $key => $uf) {
                $merge_arr = array_merge($amostras[$key], ['uf' => $uf]);
                $amostras[$key] = $merge_arr;
            }

            foreach ($this->request->data['idade'] as $key => $idade) {
                $merge_arr = array_merge($amostras[$key], ['idade' => $idade]);
                $amostras[$key] = $merge_arr;
            } 
            foreach ($this->request->data['sexo'] as $key => $sexo) {
                $merge_arr = array_merge($amostras[$key], ['sexo' => $sexo]);
                $amostras[$key] = $merge_arr;
            }

            foreach ($amostras as $key => $amostra) {
                $amostra_save = $this->Amostras->newEntity();
                $amostra_save = $this->Amostras->patchEntity($amostra_save,[
                    'code_amostra' => $amostra['amostra_id'],
                    'uf' => $amostra['uf'],
                    'idade' => $amostra['idade'],
                    'sexo' => strtoupper($amostra['sexo']),
                    'lote' => $this->generateLote($date_init)
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
                    $integration = 'Indeterminado';
                }

                $exame_find->resultado = $integration;
                $this->Exames->save($exame_find);

            }

            return $this->redirect(['action' => 'index', 'lote' => $this->generateLote($date_init)]);
              
        }
    }
    
    public function generateLote($date)
    {   
        $lote = $this->Auth->user('id') . $this->Auth->user('cliente_id') . $date;
        return $lote;
    }

    public function callIntegration($exame)
    {
        
        $url = "http://152.67.59.237/covid19/sampletest.php";
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

        $action = 'Cadastrar';
        $title = 'Amostras';

         if ($this->request->is('post')) {

            try {   
                
                if(!empty($this->request->data['file'])){
                    $file = $this->request->data['file'];

                    if($file['size'] > 0){

                        move_uploaded_file($file['tmp_name'], AMOSTRAS . $file['name']);

                        $amostra_id = explode('.', $file['name']);

                        $amostraExist = $this->Exames->find('all',[
                            'conditions' => ['amostra_id' => $amostra_id[0]]
                        ])->first();


                        if(!empty($amostraExist)){
                             throw new BadRequestException(__('Amostra já Cadastrada no Sistema.'));
                             die();
                        }

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
                            die();
                        }

                    }
                      
                }

             } catch (Exception $e) {
                  $this->Flash->error(__('Tivemos algum problema ao enviar os Exames'));
            }
         }

        $this->set(compact('action','title'));
    }

    public function relatorio()
    {
        # code...
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   

        $action = 'Ver Todas';
        $title = 'Amostras';
        // $limitDefault = 300;
        $conditions = [];

        // $this->paginate = [
        //     'limit' => $limitDefault,
        //     'contain' => ['Exames.Users']
        // ];


        if($this->Auth->user('user_type_id') == 3){
            $conditions['Exames.created_by'] = $this->Auth->user('id');
        }

        if($this->Auth->user('user_type_id') == 2){
            $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
        }
        
        if(!empty($this->request->query['amostra_id'])){
            $conditions['code_amostra'] = $this->request->query['amostra_id'];
        } 

        if(!empty($this->request->query['lote'])){
            $conditions['lote'] = $this->request->query['lote'];
        }

        if (!empty($this->request->query['data_init'])){
            $data_de = $this->request->query['data_init'];
            $conditions['cast(Amostras.created as date) >='] = $data_de;
        }

        if (!empty($this->request->query['data_fim'])){
            $data_ate = $this->request->query['data_fim'];
            $conditions['cast(Amostras.created as date) >='] = $data_ate;
         }


        $amostras = $this->Amostras->find('all',[
            'contain' => ['Exames.Users'],
             'conditions' => $conditions
        ])->toList();

        $this->set(compact('amostras','action','title'));
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
