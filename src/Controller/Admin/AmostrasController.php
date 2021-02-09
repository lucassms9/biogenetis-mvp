<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Paciente;
use Cake\Http\Client;
use DOMDocument;
use Cake\Http\Exception\BadRequestException;
use PHPExcel;
use PHPExcel_IOFactory;
use Cake\Core\Exception\Exception;
use Cake\Log\Log;


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
        $this->loadModel('AmostraErros');
        $this->loadModel('ExameOrigens');
        $this->loadModel('Origens');
        $this->loadModel('Clientes');
        $this->loadModel('ExtratoSaldo');
        $this->loadModel('Pedidos');
        $this->loadModel('EncadeamentoResultados');
        $this->loadComponent('Email');
        $this->loadComponent('Helpers');
        $this->loadComponent('PacientesData');
        $this->loadComponent('ExamesData');
    }

    public function sendEmail()
    {

        if ($this->request->is('post')) {
            ob_start(null, 0, false);
            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $conditions = [];

            $redirect = [
                'amostra_id' => $this->request->getData('amostra_id'),
                'lote' => $this->request->getData('lote'),
                'data_init' => $this->request->getData('data_init'),
                'data_fim' => $this->request->getData('data_fim'),
            ];

            if ($this->Auth->user('user_type_id') == 3) {
                $conditions['Exames.created_by'] = $this->Auth->user('id');
            }

            if ($this->Auth->user('user_type_id') == 2) {
                $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
            }

            if (!empty($this->request->getData('amostra_id'))) {
                $conditions['code_amostra'] = $this->request->getData('amostra_id');
            }

            if (!empty($this->request->getData('lote'))) {
                $conditions['lote'] = $this->request->getData('lote');
            }

            if (!empty($this->request->getData('data_init'))) {
                $data_de = $this->request->getData('data_init');
                $conditions['cast(Exames.created as date) >='] = $data_de;
            }

            if (!empty($this->request->getData('data_fim'))) {
                $data_ate = $this->request->getData('data_fim');
                $conditions['cast(Exames.created as date) >='] = $data_ate;
            }

            $amostras = $this->Amostras->find('all', [
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

            foreach ($amostras as $i => $amostra) {
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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2), $dados[$j]);
                    }
                }
            }

            $nome_arquivo = md5(date('Y-m-d_H-i-s')) . '_amostas.xls';

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

    public function encadeamentos()
    {
        $action = 'Cadastrar';
        $title = 'Resultados';

        if ($this->request->is('post')) {
            $conditions = [
                'Exames.resultado <>' => 'null'
            ];

            if (!empty($this->request->getQuery('lote'))) {
                $conditions['Amostras.lote'] = $this->request->getQuery('lote');
            }

            if (!empty($this->request->getQuery('data_init'))) {
                $data_de = $this->request->getQuery('data_init');
                $conditions['cast(Amostras.created as date) >='] = $data_de;
            }

            if (!empty($this->request->getQuery('data_fim'))) {
                $data_ate = $this->request->getQuery('data_fim');
                $conditions['cast(Amostras.created as date) >='] = $data_ate;
            }


            $encadeamentos = $this->EncadeamentoResultados->find('all', [
                'contain' => ['Encadeamento.Origens', 'ExameOrigens.Exames.Users', 'ExameOrigens.Exames.Amostras', 'ExameOrigens.Origens'],
                'conditions' => $conditions,
                'order' => ['EncadeamentoResultados.id' => 'ASC']
            ])->toList();
            $encadeamentos = $this->ExamesData->getEncadeamento($encadeamentos);
            $qtd_colunas = 11;

            $nome_colunas = [
                'Amostra ID',
                'Lote',
                'Restulado do Endpoint',
                'Nome origem',
                'URL Endpoint Request',
                'ativo',
                'tipo de equipamento',
                'tipo de amostra',
                'IAModelType',
                'IAModelName',
                'DataScience',
                'Data do Request',
            ];

            $alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');

            $objPHPExcel = new PHPExcel();

            for ($i = 0; $i <= $qtd_colunas; $i++)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$i] . '1', $nome_colunas[$i]);


            foreach ($encadeamentos as $i => $encadeamentoObj) {


                if (!empty($encadeamentoObj->encadeamento->origen)) {

                    $url_request = $encadeamentoObj->encadeamento->origen->url_request;
                    $equip_tipo =  $encadeamentoObj->encadeamento->origen->equip_tipo;
                    $amostra_tipo = $encadeamentoObj->encadeamento->origen->amostra_tipo;
                    $IAModelType = $encadeamentoObj->encadeamento->origen->IAModelType;
                    $IAModelName = $encadeamentoObj->encadeamento->origen->IAModelName;
                    $DataScience = $encadeamentoObj->encadeamento->origen->DataScience;
                } else {

                    $url_request = $encadeamentoObj->exame_origen->origen->url_request;
                    $equip_tipo =  $encadeamentoObj->exame_origen->origen->equip_tipo;
                    $amostra_tipo = $encadeamentoObj->exame_origen->origen->amostra_tipo;
                    $IAModelType = $encadeamentoObj->exame_origen->origen->IAModelType;
                    $IAModelName = $encadeamentoObj->exame_origen->origen->IAModelName;
                    $DataScience = $encadeamentoObj->exame_origen->origen->DataScience;
                }


                $dados = [
                    $encadeamentoObj->exame_origen->exame->amostra_id,
                    @$encadeamentoObj->exame_origen->exame->amostra->lote,
                    $encadeamentoObj->resultado,
                    $encadeamentoObj->exame_origen->origen->nome_origem,
                    $url_request,
                    $encadeamentoObj->exame_origen->origen->ativo,
                    $equip_tipo,
                    $amostra_tipo,
                    $IAModelType,
                    $IAModelName,
                    $DataScience,
                    $encadeamentoObj->exame_origen->data_request->i18nFormat('dd/MM/yyyy HH:mm'),
                ];

                for ($j = 0; $j <= $qtd_colunas; $j++) {
                    if (isset($alfabeto[$j])) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2), $dados[$j]);
                    }
                }


                // if(count($amostra->origen->encadeamentos) > 0){
                //     foreach ($amostra->origen->encadeamentos as $key => $encadeamento) {
                //         $i++;
                //         $equip_tipo =  $encadeamento->origen->equip_tipo;
                //         $amostra_tipo = $encadeamento->origen->amostra_tipo;
                //         $IAModelType = $encadeamento->origen->IAModelType;
                //         $IAModelName = $encadeamento->origen->IAModelName;
                //         $DataScience = $encadeamento->origen->DataScience;

                //         $dados = [
                //             $amostra->exame->amostra_id,
                //             $amostra->exame->amostra->lote,
                //             $amostra->resultado,
                //             $amostra->origen->nome_origem,
                //             $amostra->origen->url_request,
                //             $amostra->origen->ativo,
                //             $equip_tipo,
                //             $amostra_tipo,
                //             $IAModelType,
                //             $IAModelName,
                //             $DataScience,
                //             $amostra->data_request->i18nFormat('dd/MM/yyyy HH:mm'),
                //         ];


                //         for ($j = 0; $j <= $qtd_colunas; $j++) {
                //             if (isset($alfabeto[$j])) {
                //                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 4),$dados[$j]);
                //             }
                //         }

                //     }

                // }


            }

            $arquivo = 'encadeamentos_' . date('Y-m-d-H-i-s');

            // Redirect output to a client’s web browser (Excel5)
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment;filename=$arquivo.xls");
            header("Cache-Control: max-age=0");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            die();
        }

        $this->set(compact('action', 'title'));
    }

    public function resultados()
    {
        $action = 'Cadastrar';
        $title = 'Resultados';

        if ($this->request->is('post')) {
            $conditions = [
                'Exames.resultado =' => '1'
            ];

            if ($this->Auth->user('user_type_id') != 1) {
                $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
            }

            if (!empty($this->request->getData('lote'))) {
                $conditions['Amostras.lote'] = $this->request->getData('lote');
            }

            if (!empty($this->request->getData('data_init'))) {
                $data_de = $this->request->getData('data_init');
                $conditions['cast(Amostras.created as date) >='] = $data_de;
            }

            if (!empty($this->request->getData('data_fim'))) {
                $data_ate = $this->request->getData('data_fim');
                $conditions['cast(Amostras.created as date) >='] = $data_ate;
            }


            $amostras = $this->ExameOrigens->find('all', [
                'contain' => ['Origens', 'Exames.Users', 'Exames.Amostras', 'Exames.Origens'],
                'conditions' => $conditions
            ])->toList();

            $qtd_colunas = 12;

            $nome_colunas = [
                'Amostra ID',
                'Lote',
                'Restulado do Endpoint',
                'Nome origem',
                'URL Endpoint Request',
                'Endpoint Resp. pelo Resultado',
                'ativo',
                'tipo de equipamento',
                'tipo de amostra',
                'IAModelType',
                'IAModelName',
                'DataScience',
                'Data do Request',
            ];

            $alfabeto = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');

            $objPHPExcel = new PHPExcel();

            for ($i = 0; $i <= $qtd_colunas; $i++)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$i] . '1', $nome_colunas[$i]);


            $amostras = $this->ExamesData->getExamesResult($amostras);
            foreach ($amostras as $i => $amostra) {

                if ($amostra->exame->origen) {
                    $url_encad = $amostra->exame->origen->url_request;
                    $equip_tipo =  $amostra->exame->origen->equip_tipo;
                    $amostra_tipo = $amostra->exame->origen->amostra_tipo;
                    $IAModelType = $amostra->exame->origen->IAModelType;
                    $IAModelName = $amostra->exame->origen->IAModelName;
                    $DataScience = $amostra->exame->origen->DataScience;
                } else {
                    $url_encad = 'Sem Encadeamentos';
                    $equip_tipo =  $amostra->origen->equip_tipo;
                    $amostra_tipo = $amostra->origen->amostra_tipo;
                    $IAModelType = $amostra->origen->IAModelType;
                    $IAModelName = $amostra->origen->IAModelName;
                    $DataScience = $amostra->origen->DataScience;
                }

                $dados = [
                    $amostra->exame->amostra_id,
                    @$amostra->exame->amostra->lote,
                    $amostra->resultado,
                    $amostra->origen->nome_origem,
                    $amostra->origen->url_request,
                    $url_encad,
                    $amostra->origen->ativo,
                    $equip_tipo,
                    $amostra_tipo,
                    $IAModelType,
                    $IAModelName,
                    $DataScience,
                    $amostra->data_request->i18nFormat('dd/MM/yyyy HH:mm'),
                ];

                for ($j = 0; $j <= $qtd_colunas; $j++) {
                    if (isset($alfabeto[$j])) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2), $dados[$j]);
                    }
                }
            }

            $arquivo = 'resultado_geral_' . date('Y-m-d-H-i-s');

            // Redirect output to a client’s web browser (Excel5)
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment;filename=$arquivo.xls");
            header("Cache-Control: max-age=0");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            die();
        }

        $this->set(compact('action', 'title'));
    }

    public function generateExcel()
    {

        if ($this->request->is('post')) {
            $conditions = [];

            ob_start(null, 0, false);
            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            if ($this->Auth->user('user_type_id') == 3) {
                $conditions['Exames.created_by'] = $this->Auth->user('id');
            }

            if ($this->Auth->user('user_type_id') == 2) {
                $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
            }

            if (!empty($this->request->getData('amostra_id'))) {
                $conditions['code_amostra'] = $this->request->getData('amostra_id');
            }

            if (!empty($this->request->getData('lote'))) {
                $conditions['lote'] = $this->request->getData('lote');
            }

            if (!empty($this->request->getData('data_init'))) {
                $data_de = $this->request->getData('data_init');
                $conditions['cast(Exames.created as date) >='] = $data_de;
            }

            if (!empty($this->request->getData('data_fim'))) {
                $data_ate = $this->request->getData('data_fim');
                $conditions['cast(Exames.created as date) >='] = $data_ate;
            }

            $amostras = $this->Amostras->find('all', [
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

            foreach ($amostras as $i => $amostra) {
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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alfabeto[$j] . ($i + 2), $dados[$j]);
                    }
                }
            }

            $arquivo = 'amostras_' . date('Y-m-d-H-i-s');

            // Redirect output to a client’s web browser (Excel5)
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment;filename=$arquivo.xls");
            header("Cache-Control: max-age=0");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            die();
        }
    }

    public function sendData()
    {
        if ($this->request->is('post')) {

            ob_start(null, 0, false);
            ini_set("memory_limit", -1);
            ini_set('max_execution_time', 0);
            set_time_limit(0);

            $date_init = date('YmdHi');
            $amostras = [];

            $itemFor = $this->request->getData('totalFiles') - $this->request->getData('filesRemoved');

            foreach ($this->request->getData('amostraid') as $key => $amostraid) {
                $amostras[$key] = ['amostra_id' => $amostraid];
            }
            foreach ($this->request->getData('uf') as $key => $uf) {
                $merge_arr = array_merge($amostras[$key], ['uf' => $uf]);
                $amostras[$key] = $merge_arr;
            }

            foreach ($this->request->getData('idade') as $key => $idade) {
                $merge_arr = array_merge($amostras[$key], ['idade' => $idade]);
                $amostras[$key] = $merge_arr;
            }
            foreach ($this->request->getData('sexo') as $key => $sexo) {
                $merge_arr = array_merge($amostras[$key], ['sexo' => $sexo]);
                $amostras[$key] = $merge_arr;
            }

            foreach ($amostras as $key => $amostra) {

                $amostra_save = $this->Amostras->newEntity();
                $amostra_save = $this->Amostras->patchEntity($amostra_save, [
                    'code_amostra' => $amostra['amostra_id'],
                    'uf' => @$amostra['uf'],
                    'idade' => @$amostra['idade'],
                    'sexo' => strtoupper(@$amostra['sexo']),
                    'lote' => $this->generateLote($date_init)
                ]);

                $amostra_save = $this->Amostras->save($amostra_save);


                // FAZ COMUNICAO COM O SERVICO DE IA
                // SALVA O RETORNO EM RESULTADO

                $exame_find = $this->Exames->find('all', [
                    'contain' => ['ExameOrigens.Origens.Encadeamentos.Origens'],
                    'conditions' => ['amostra_id' => $amostra['amostra_id']]
                ])->first();

                $integration = $this->callIntegration($exame_find);

                $exame_find->resultado = '1';

                $this->ExamesData->save($exame_find->hash, $integration);

                if (!empty($exame_find->pedido_id)) {
                    $pedido = $this->Pedidos->get($exame_find->pedido_id);
                    $pedido->status = 'Finalizado';
                    $this->Pedidos->save($pedido);
                }
                $exame_find->result = 1;
                $this->Exames->save($exame_find);

                //gravando saldo
                $dataSave = [
                    'cliente_id' => $this->Auth->user('cliente_id'),
                    'type' => 'D',
                    'valor' => 1,
                    'created_by' => $this->Auth->user('id')
                ];

                $extratoSaldo = $this->ExtratoSaldo->newEntity();
                $extratoSaldo = $this->ExtratoSaldo->patchEntity($extratoSaldo, $dataSave);
                $extratoSaldo = $this->ExtratoSaldo->save($extratoSaldo);
            }

            return $this->redirect(['action' => 'index', 'lote' => $this->generateLote($date_init)]);
        }
    }

    public function generateLote($date)
    {
        $lote = $this->Auth->user('id') . $this->Auth->user('cliente_id') . $date;
        return $lote;
    }
    public function requestEndpoints($encad, $filedata, $isLastRequest)
    {

        $parse_status = [
            'Positivo' => 'Positive',
            'Negativo' => 'Negative'
        ];

        $http = new Client();
        $response = $http->post($encad->origen->url_request, [
            'Userfile' => fopen($filedata, 'r'),
        ]);

        if ((strpos($encad->origen->url_request, '172.21.1.2') !== false)) {
            $result = $response->getJson();
            $result = $result['retorno'];
        } else {
            $result = $this->html_to_obj($response->getStringBody());
        }

        $status_branch = @$parse_status[$encad->regra];

        if ($isLastRequest) {
            $result = ['stop_loop' => true, 'result' => $result];
        } else if ((strpos($result, $status_branch)) !== false) {
            $result = ['stop_loop' => true, 'result' => $result];
        } else {
            $result = ['stop_loop' => false, 'result' => $result];
        }

        return $result;
    }

    public function callIntegration($exame)
    {
        $positivo = 0;
        $negativo = 0;
        $inadequado = 0;

        $parse_status = [
            'Positivo' => 'Positive',
            'Negativo' => 'Negative',
            'Invalid' => 'Invalid'
        ];

        foreach ($exame->exame_origens as $origem) {

            $url = $origem->origen->url_request;
            $filedata = AMOSTRAS . $exame->amostra_id . '.' . $exame->file_extesion;


            $http = new Client();
            $response = $http->post($url, [
                'Userfile' => fopen($filedata, 'r'),
            ]);

            $total_enc = count($origem->origen->encadeamentos);

            $isEncadeado = $total_enc > 0 ? true : false;

            if ((strpos($url, '172.21.1.2') !== false)) {
                $result = $response->getJson();
                $result = $result['retorno'];
            } else {
                $result = $this->html_to_obj($response->getStringBody());
            }

            Log::debug('callIntegration - resutlado: ' . $result);

            $status_main = @$parse_status[$origem->origen->regra_encadeamento] ?? 'Invalid';
            $stop_loop = false;

            $sum_request = 0;
            $origem_saved = '';

            $encadeamentoResul = $this->EncadeamentoResultados->newEntity();
            $dados_save = [
                'exame_origem_id' => $origem->id,
                'encadeamento_id' => null,
                'resultado' => '1',
                'hash' => $this->Helpers->doEncrypt($origem->id . 'FTC' . $this->Helpers->generateRandomString(30))
            ];
            $encadeamentoResul = $this->EncadeamentoResultados->patchEntity($encadeamentoResul, $dados_save);
            $encadeamentoResul = $this->EncadeamentoResultados->save($encadeamentoResul);

            $this->ExamesData->saveEncadeamento($encadeamentoResul->hash, $result);
            //tratamento encadeamentos
            if ((strpos($result, $status_main)) === false && $isEncadeado) {
                foreach ($origem->origen->encadeamentos as $key => $encadeamento) {
                    $sum_request++;

                    if ($stop_loop) {
                        break;
                    }

                    $last_request = $sum_request == $total_enc ? true : false;
                    $handle = $this->requestEndpoints($encadeamento, $filedata, $last_request);

                    $stop_loop = $handle['stop_loop'];
                    $result = $handle['result'];
                    $origem_saved = $encadeamento->origen->id;

                    $encadeamentoResul = $this->EncadeamentoResultados->newEntity();
                    $dados_save = [
                        'exame_origem_id' => $origem->id,
                        'encadeamento_id' => $encadeamento->id,
                        'resultado' => '1',
                        'hash' =>  $this->Helpers->doEncrypt($origem->id . 'CKC' . $this->Helpers->generateRandomString(30))
                    ];
                    $encadeamentoResul = $this->EncadeamentoResultados->patchEntity($encadeamentoResul, $dados_save);
                    $encadeamentoResul = $this->EncadeamentoResultados->save($encadeamentoResul);
                    $this->ExamesData->saveEncadeamento($encadeamentoResul->hash, $result);
                }

                if (!empty($origem_saved)) {
                    $get_exame = $this->Exames->get($exame->id);
                    $get_exame->origem_id = $origem_saved;
                    $get_exame = $this->Exames->save($get_exame);
                }
            }

            if ((strpos($result, 'Positive')) !== false) {
                $integration = 'Positivo';
                $positivo++;
            } else if ((strpos($result, 'Negative')) !== false) {
                $integration = 'Negativo';
                $negativo++;
            } else {
                $integration = 'Indeterminado';
                $inadequado++;
            }

            $origem->data_request = date('Y-m-d H:i:s');
            $origem->resultado = '1';

            $this->ExamesData->saveOrigem($origem->hash, $integration);
            $this->ExameOrigens->save($origem);
        }

        if (($positivo + $negativo + $inadequado) > 0) {
            //so vai gravar inadequado se todos retornos forem inadequados
            if (($inadequado == ($positivo + $negativo + $inadequado))) {
                $result = 'Indeterminado';
            } else {
                if (($positivo > $negativo) && ($positivo + $negativo) > 0) {
                    $result = 'Positivo';
                } else if ($positivo < $negativo && ($positivo + $negativo) > 0) {
                    $result = 'Negativo';
                } else {
                    $result = 'Indeterminado';
                }
            }
        } else {
            $result = 'Indeterminado';
        }

        return $result;
    }


    public function html_to_obj($html)
    {
        try {
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $getElement = $this->element_to_obj($dom->documentElement);
            $result = trim($getElement['children'][0]['children'][1]['html']);
            $regexTopper = preg_split('/\s/', $result);
            return trim($regexTopper[0]);
        } catch (Exception $e) {
            throw new Exception($e->message, 1);
        }
    }

    public function element_to_obj($element)
    {
        $obj = array("tag" => $element->tagName);
        foreach ($element->attributes as $attribute) {
            $obj[$attribute->name] = $attribute->value;
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj["html"] = $subElement->wholeText;
            } else {
                $obj["children"][] = $this->element_to_obj($subElement);
            }
        }
        return $obj;
    }

    public function setOrigens($exame)
    {

        $conditions = [
            'amostra_tipo' => $exame->amostra_tipo,
            'equip_tipo' => $exame->equip_tipo,
            'ativo' => 1
        ];

        $origensByExame = $this->Origens->find('all', [
            'conditions' => $conditions
        ])->toList();

        foreach ($origensByExame as $key => $origem) {
            $dados = [
                'exame_id' => $exame->id,
                'origem_id' => $origem->id,
                'resultado' => '0',
                'hash' =>  $this->Helpers->doEncrypt($origem->id . 'FTC' . $this->Helpers->generateRandomString(30))
            ];
            $newOriginExame = $this->ExameOrigens->newEntity();
            $newOriginExame = $this->ExameOrigens->patchEntity($newOriginExame, $dados);
            $newOriginExame = $this->ExameOrigens->save($newOriginExame);


            $this->ExamesData->saveOrigem($newOriginExame->hash, '');
        }

        return true;
    }

    public function getBetterRestul($file_extesion, $file)
    {
        /**
         * type_file = 1 - EQUIPAMENTO LCMS
         * type_file = 2 - EQUIPAMENTO FTIR
         */

        $file_extesion = strtolower($file_extesion);
        $value = 0;
        $frase_key = '#MS Peaks One';
        $type_file = 1;
        $amostra_tipo = strpos(strtolower($file['name']), 'swab') !== FALSE ? 'SWAB' : 'SALIVA';

        $equip_tipo = '';

        if ($file_extesion == 'csv') {
            $file = fopen(AMOSTRAS . $file['name'], 'r');
            while ($line = fgetcsv($file)) {

                if (strpos($line[0], $frase_key) !== FALSE) {
                    $type_file = 2;
                }

                // if(is_numeric($line) ){
                //     if($line[1] > $value){
                //         $value = $line[1];
                //     }
                // }
            }

            $equip_tipo = $type_file === 1 ? 'FTIR' : 'LCMS';

            return ['value' => $value, 'type_file' => $type_file, 'file_extesion' => 'csv', 'equip_tipo' => $equip_tipo, 'amostra_tipo' => $amostra_tipo];
        } else if ($file_extesion == 'txt') {
            $file = fopen(AMOSTRAS . $file['name'], 'r');

            while ($line = fgets($file)) {
                if (strpos($line[0], $frase_key) !== FALSE) {
                    $type_file = 2;
                }
                if (is_numeric($line[1])) {
                    $line_new = preg_split('/\s+/', trim($line));
                    if ($line_new[1] > $value) {
                        $value = $line_new[1];
                    }
                }
            }

            fclose($file);
            $equip_tipo = $type_file === 1 ? 'FTIR' : 'LCMS';

            return ['value' => $value, 'type_file' => $type_file, 'file_extesion' => 'txt', 'equip_tipo' => $equip_tipo, 'amostra_tipo' => $amostra_tipo];
        } else {
            $equip_tipo = $type_file === 1 ? 'FTIR' : 'LCMS';
            return ['value' => $value, 'type_file' => $type_file, 'file_extesion' => 'xls', 'equip_tipo' => $equip_tipo, 'amostra_tipo' => $amostra_tipo];
        }
    }

    public function import()
    {

        $action = 'Cadastrar';
        $title = 'Amostras';
        if ($this->request->is('post')) {
            try {
                if (!empty($this->request->getData('file'))) {
                    $file = $this->request->getData('file');
                    $filext =  explode(".", $file['name']);

                    if ($filext[sizeof($filext) - 1] !== "csv" && $filext[sizeof($filext) - 1]  !== "xls" && $filext[sizeof($filext) - 1] !== "xls") {
                        throw new BadRequestException(__('Extensão inválida.'));
                    }
                    if ($file['size'] > 0) {
                        move_uploaded_file($file['tmp_name'], AMOSTRAS . $file['name']);

                        $amostra_id = explode('.', $file['name']);
                        $file_extesion = $amostra_id[1];

                        $amostraExist = $this->Exames->find('all', [
                            'contain' => ['Users.Clientes'],
                            'conditions' => [
                                'amostra_id' => $amostra_id[0],
                                'Clientes.id' => $this->Auth->user('cliente_id'),
                                'resultado <>' => 'null'
                            ]
                        ])->first();

                        $handle_file = $this->getBetterRestul($file_extesion, $file);

                        if (!empty($amostraExist)) {
                            throw new BadRequestException(__('Amostra já Cadastrada no Sistema.'));
                            die();
                        }

                        $amostraExist2 = $this->Exames->find('all', [
                            'contain' => ['Users.Clientes'],
                            'conditions' => [
                                'amostra_id' => $amostra_id[0],
                                'Clientes.id' => $this->Auth->user('cliente_id'),
                            ]
                        ])->first();

                        if(!empty($amostraExist2)){
                            $this->Exames->delete($amostraExist2);
                        }

                        $cliente = $this->Clientes->get($this->Auth->user('cliente_id'), [
                            'contain' => [],
                        ]);

                        $saldo = $cliente->getSaldo();


                        if (isset($saldo) && $saldo <= 0) {
                            throw new BadRequestException(__('Seu Saldo para exames esgotou!'));
                            die();
                        }

                        $clear_name_file = $this->Helpers->stringToNumber($file['name']);

                        $pedido = $this->Pedidos->find('all', [
                            'conditions' =>
                            [
                                'codigo_pedido like' => '%' . $clear_name_file . '%',
                                'cliente_id' => $this->Auth->user('cliente_id')
                            ]
                        ])->first();

                        $exame = [
                            'amostra_id' => $amostra_id[0],
                            'file_extesion' => $handle_file['file_extesion'],
                            'equip_tipo' => $handle_file['equip_tipo'],
                            'amostra_tipo' => $handle_file['amostra_tipo'],
                            'file_name' => $file['name'],
                            'pedido_id' => 0,
                            'created_by' => $this->Auth->user('id'),
                        ];

                        if (!empty($pedido)) {
                            $exame['pedido_id'] = $pedido->id;
                        }

                        $exame_save = $this->Exames->newEntity();
                        $exame_save = $this->Exames->patchEntity($exame_save, $exame);
                        $exame_save = $this->Exames->save($exame_save);
                        $exame_save->hash =   $this->Helpers->doEncrypt($exame_save->id . 'FTC' . $this->Helpers->generateRandomString(30));
                        $exame_save = $this->Exames->save($exame_save);


                        $res = $this->ExamesData->save($exame_save->hash, '');

                        if ($exame_save) {
                            $exame_save = $this->Exames->get($exame_save->id, [
                                'contain' => ['Pedidos.Anamneses.Pacientes']
                            ]);
                            //seta as origens para disparo de request
                            $this->setOrigens($exame_save);
                            if ($exame_save->pedido) {
                                $resPaciente = $this->PacientesData->getByHash($exame_save->pedido->anamnese->paciente->hash);
                                if ($res = json_decode($resPaciente, true)) {
                                    $exame_save->pedido->anamnese->paciente = new Paciente($res);
                                }
                            }
                            echo json_encode($exame_save);
                            exit;
                        } else {
                            throw new Exception("Error Processing Request", 1);
                            die();
                        }
                    }
                }
            } catch (BadRequestException $e) {
                throw new BadRequestException($e->getMessage());
                die();
            }
        }

        $this->set(compact('action', 'title'));
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
        $conditions = [
            'Exames.resultado <>' => 'null'
        ];

        if ($this->Auth->user('user_type_id') != 1) {
            $conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
        }

        if (!empty($this->request->getQuery('amostra_id'))) {
            $conditions['code_amostra'] = $this->request->getQuery('amostra_id');
        }

        if (!empty($this->request->getQuery('lote'))) {
            $conditions['lote'] = $this->request->getQuery('lote');
        }

        if (!empty($this->request->getQuery('data_init'))) {
            $data_de = $this->request->getQuery('data_init');
            $conditions['cast(Amostras.created as date) >='] = $data_de;
        }

        if (!empty($this->request->getQuery('data_fim'))) {
            $data_ate = $this->request->getQuery('data_fim');
            $conditions['cast(Amostras.created as date) >='] = $data_ate;
        }


        $this->paginate = [
            // 'limit' => '100',
            'contain' => ['Exames.Users'],
            'conditions' => $conditions
        ];

        $amostras = $this->paginate($this->Amostras)->toList();
        // debug($amostras)
        // $amostras = $this->Amostras->find('all', [
        //     'contain' => ['Exames.Users'],
        //     'conditions' => $conditions
        // ])->toList();
        $arr_hashs = [];
        if (is_array($amostras)) {
            for ($i = 0; $i < sizeof($amostras); $i++) {
                array_push($arr_hashs, $amostras[$i]->exame->hash);
            }
        } else {
            array_push($arr_hashs, $amostras->exame->hash);
        }

        $result_hashs = $this->ExamesData->get($arr_hashs);

        if (is_array($result_hashs)) {
            for ($i = 0; $i < sizeof($amostras); $i++) {
                for ($z = 0; $z < sizeof($result_hashs); $z++) {
                    if ($result_hashs[$z]->hash == $amostras[$i]->exame->hash) {
                        $amostras[$i]->exame->resultado = $result_hashs[$z]->body;
                        array_splice($result_hashs, $z, 1);
                        break 1;
                    }
                }
            }
        }
        $this->set(compact('amostras', 'action', 'title'));
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
