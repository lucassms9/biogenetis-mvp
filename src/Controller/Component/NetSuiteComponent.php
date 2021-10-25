<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Paciente;
use Cake\Http\Client\FormData;
use Cake\Utility\Xml;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class NetSuiteComponent extends Component
{
    public $components = ['ExamesData', 'PacientesData'];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->client = new Client();
        $this->ACCOUNT_ID = env('ACCOUNT_ID');
        $this->CONSUMER_KEY = env('CONSUMER_KEY');
        $this->CONSUMER_SECRET = env('CONSUMER_SECRET');
        $this->TOKEN_ID = env('TOKEN_ID');
        $this->TOKEN_SECRET = env('TOKEN_SECRET');
        $this->WEBSERVICE_URL = env('WEBSERVICE_URL');

        $this->Pedidos = TableRegistry::get('Pedidos');
        $this->Configuracoes = TableRegistry::get('Configuracoes');
    }

    private function generateHeader()
    {
        $date = date_create();
        $timestamp = substr(date_timestamp_get($date), 0, 10);

        $bytes = random_bytes(10);
        $nonce = bin2hex($bytes);

        $baseString = "$this->ACCOUNT_ID&$this->CONSUMER_KEY&$this->TOKEN_ID&$nonce&$timestamp";

        $key = "$this->CONSUMER_SECRET&$this->TOKEN_SECRET";

        $signature = base64_encode(hash_hmac('sha1', $baseString, $key, true));


        // debug($this->ACCOUNT_ID);
        // debug($this->CONSUMER_KEY);
        // debug($this->TOKEN_ID);
        // debug($nonce);
        // debug($timestamp);
        // debug($signature);
        // die;

        return '<soap-env:Header>
        <tokenPassport>
          <account>' . $this->ACCOUNT_ID . '</account>
          <consumerKey>' . $this->CONSUMER_KEY . '</consumerKey>
          <token>' . $this->TOKEN_ID . '</token>
          <nonce>' . $nonce . '</nonce>
          <timestamp>' . $timestamp . '</timestamp>
          <signature algorithm="HMAC-SHA1">' . $signature . '</signature>
        </tokenPassport>
        <preferences>
          <runServerSuiteScriptAndTriggerWorkflows>
            false
          </runServerSuiteScriptAndTriggerWorkflows>
        </preferences>
        <searchPreferences>
          <pageSize>1000</pageSize>
          <bodyFieldsOnly>false</bodyFieldsOnly>
        </searchPreferences>
      </soap-env:Header>';
    }

    private function getStatus($xmlArray, $type_response, $mode)
    {
        if (@$xmlArray['Envelope']['soapenv:Body']['soapenv:Fault']) {
            return [
                '@isSuccess' => 'false',
                'platformCore:statusDetail' => [
                    'platformCore:message' =>  $xmlArray['Envelope']['soapenv:Body']['soapenv:Fault']['faultstring']
                ]
            ];
        }

        return $xmlArray['Envelope']['soapenv:Body'][$type_response][$mode]['platformCore:status'];
    }

    private function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    public function getClienteById($internalId)
    {

        $xml = '<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
        ' . $this->generateHeader() . '
        <soap-env:Body>
          <get xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
            <baseRef internalId="' . $internalId . '" type="customer" xsi:type="platformCore:RecordRef">
              <platformCore:name/>
            </baseRef>
          </get>
        </soap-env:Body>
      </soap-env:Envelope>';

        // Send the request.
        $response = $this->client->post(
            $this->WEBSERVICE_URL,
            $xml,
            [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'SOAPAction' => 'get',
                ]
            ]
        );
        $res_xml = $response->getStringBody();

        $xmlArray = Xml::toArray(Xml::build($res_xml));

        $status = $this->getStatus($xmlArray, 'getResponse', 'platformMsgs:readResponse');


        if ($status['@isSuccess'] == false) {

            throw new Exception(@$status['platformCore:statusDetail']['platformCore:message']);
        }

        debug($xmlArray['Envelope']['soapenv:Body']['getResponse']['platformMsgs:readResponse']['platformMsgs:record']);
        die;
    }

    public function createClientePF($data)
    {
        // $data = [
        //     'externalId',
        //     'nome',
        //     'sobrenome',
        //     'email',
        //     'endereco',
        //     'estado',
        //     'cep',
        //     'municipio'
        // ];

        $xml = '<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
        ' . $this->generateHeader() . '
        <soap-env:Body>
        <upsert xmlns="urn:messages_2020_1.platform.webservices.netsuite.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            <!--externalId deve ser informado o Id do cliente no sistema de origem -->
            <record xmlns:cust="urn:relationships_2020_1.lists.webservices.netsuite.com" xsi:type="cust:Customer" externalId="' . $data['externalId'] . '">
                <!--Nome -->
                <cust:firstName>' . $data['nome'] . '</cust:firstName>
                <!--Sobrenome -->
                <cust:lastName>' . $data['sobrenome'] . '</cust:lastName>

                <!--Telefone principal -->
                <cust:phone>' . $data['phone1'] . '</cust:phone>

                <!--Telefone alternativo -->
                <cust:altPhone>' . $data['phone2'] . '</cust:altPhone>

                <!--Email -->
                <cust:email>' . $data['email'] . '</cust:email>
                <!--Subsidiária -->
                <cust:subsidiary internalId="3" />
                <!--Identificador pessoa física(true) / jurídica(false) -->
                <cust:isPerson>true</cust:isPerson>
                <!--Status do cliente enviar sempre 13 -->
                <cust:entityStatus>13</cust:entityStatus>
                <cust:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                    <!--Optante pelo simples nacional -->
                    <platformCore:customField internalId="2231" scriptId="custentity_sit_l_opt_simples" xsi:type="platformCore:SelectCustomFieldRef">
                        <platformCore:value internalId="1" typeId="331" />
                    </platformCore:customField>
                    <platformCore:customField internalId="2231" scriptId="custentity_psg_br_cpf" xsi:type="platformCore:StringCustomFieldRef">
                        <platformCore:value>' . $data['externalId'] . '</platformCore:value>
                    </platformCore:customField>
                </cust:customFieldList>
                <!--Lista de endereços -->
                <cust:addressbookList replaceAll="false" xsi:type="cust:CustomerAddressbookList">
                    <!--Endereço -->
                    <cust:addressbook xsi:type="cust:CustomerAddressbook">
                        <!-- Endereço padrão  -->
                        <cust:defaultShipping>true</cust:defaultShipping>
                        <cust:defaultBilling>true</cust:defaultBilling>
                        <!--Residencial/Comercial -->
                        <cust:isResidential>false</cust:isResidential>
                        <cust:label>' . $data['endereco'] . '</cust:label>
                        <cust:addressbookAddress xmlns:platformCommon="urn:common_2020_1.platform.webservices.netsuite.com">
                            <!--País -->
                            <platformCommon:country>_brazil</platformCommon:country>
                            <!--Logradouro -->
                            <platformCommon:addr1>' . $data['endereco'] . '</platformCommon:addr1>
                            <!--Estado -->
                            <platformCommon:state>' . $data['estado'] . '</platformCommon:state>
                            <!--CEP -->
                            <platformCommon:zip>' . $data['cep'] . '</platformCommon:zip>
                            <platformCommon:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                                <!--Complemento-->
                                <platformCore:customField internalId="2159" scriptId="custrecord_sit_address_i_numero" xsi:type="platformCore:StringCustomFieldRef">
                                    <platformCore:value>0</platformCore:value>
                                </platformCore:customField>

                                <!--Município-->
                                <platformCore:customField internalId="2154" scriptId="custrecord_o2g_address_l_mun" xsi:type="platformCore:SelectCustomFieldRef">
                                    <platformCore:value internalId="5936" typeId="133">
                                        <platformCore:name>' . $data['municipio'] . '</platformCore:name>
                                    </platformCore:value>
                                </platformCore:customField>
                                <!--Tipo Logradouro-->
                                <platformCore:customField internalId="2165" scriptId="custrecord_sit_address_l_tp_logr" xsi:type="platformCore:SelectCustomFieldRef">
                                    <platformCore:value internalId="26" typeId="345">
                                        <platformCore:name>Rua</platformCore:name>
                                    </platformCore:value>
                                </platformCore:customField>
                            </platformCommon:customFieldList>
                        </cust:addressbookAddress>
                    </cust:addressbook>
                </cust:addressbookList>
            </record>
        </upsert>
        </soap-env:Body>
      </soap-env:Envelope>';

        // Send the request.
        $response = $this->client->post(
            $this->WEBSERVICE_URL,
            $xml,
            [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'SOAPAction' => 'upsert',
                ]
            ]
        );
        $res_xml = $response->getStringBody();

        $xmlArray = Xml::toArray(Xml::build($res_xml));

        $status = $this->getStatus($xmlArray, 'upsertResponse', 'writeResponse');


        if ($status['@isSuccess'] == false) {

            throw new Exception(@$status['platformCore:statusDetail']['platformCore:message']);
        }


        return $xmlArray['Envelope']['soapenv:Body']['upsertResponse']['writeResponse']['baseRef']['@internalId'];
    }

    public function createClientePJ($data)
    {

        // $data = [
        //     'externalId',
        //     'nome',
        //     'sobrenome',
        //     'email',
        //     'endereco',
        //     'bairro',
        //     'estado',
        //     'cep',
        //     'municipio'
        // ];

        $xml = '<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
        ' . $this->generateHeader() . '
        <soap-env:Body>
          <upsert xmlns="urn:messages_2020_1.platform.webservices.netsuite.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <!--externalId deve ser informado o Id do cliente no sistema de origem -->
        <record xmlns:cust="urn:relationships_2020_1.lists.webservices.netsuite.com" xsi:type="cust:Customer" externalId="' . $data['externalId'] . '">
            <!--Nome da empresa - Razao Social -->
            <cust:companyName>' . $data['sobrenome'] . '</cust:companyName>

            <!--Telefone principal -->
            <cust:phone>' . $data['phone1'] . '</cust:phone>

             <!--Telefone alternativo -->
             <cust:altPhone>' . $data['phone2'] . '</cust:altPhone>

            <!--Email -->
            <cust:email>' . $data['email'] . '</cust:email>

            <!--Subsidiária -->
            <cust:subsidiary internalId="3" />

            <!--Representante de vendas -->
            <!-- <cust:salesRep internalId="7" /> -->

            <!--Identificador pessoa física(true) / jurídica(false) -->
            <cust:isPerson>false</cust:isPerson>

            <!--Status do cliente enviar sempre 13 -->
            <cust:entityStatus>13</cust:entityStatus>
            <cust:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                <!--Optante pelo simples nacional -->
                <platformCore:customField internalId="2231" scriptId="custentity_sit_l_opt_simples" xsi:type="platformCore:SelectCustomFieldRef">
                    <platformCore:value internalId="1" typeId="331" />
                </platformCore:customField>
                <!--Nome Fantasia -->
                <platformCore:customField internalId="2670" scriptId="custentity_bio_nome_fantasia" xsi:type="platformCore:SelectCustomFieldRef">
                    <platformCore:value>' . $data['sobrenome'] . '</platformCore:value>
                </platformCore:customField>

                <!--CNPJ -->
                <platformCore:customField internalId="323" scriptId="custentity_psg_br_cnpj" xsi:type="platformCore:StringCustomFieldRef">
                    <platformCore:value>' . $data['externalId'] . '</platformCore:value>
                </platformCore:customField>

            </cust:customFieldList>

            <!--Lista de endereços -->
            <cust:addressbookList replaceAll="false" xsi:type="cust:CustomerAddressbookList">
                <!--Endereço -->
                <cust:addressbook xsi:type="cust:CustomerAddressbook">
                    <!-- Endereço padrão  -->
                    <cust:defaultShipping>true</cust:defaultShipping>
                    <cust:defaultBilling>true</cust:defaultBilling>

                    <!--Residencial/Comercial -->
                    <cust:isResidential>false</cust:isResidential>
                    <cust:label>' . $data['endereco'] . '</cust:label>
                    <cust:addressbookAddress xmlns:platformCommon="urn:common_2020_1.platform.webservices.netsuite.com">

                        <!--País -->
                        <platformCommon:country>_brazil</platformCommon:country>

                        <!--Logradouro -->
                        <platformCommon:addr1>' . $data['endereco'] . '</platformCommon:addr1>
                        <!--Estado -->
                        <platformCommon:state>' . $data['estado'] . '</platformCommon:state>
                        <!--CEP -->
                        <platformCommon:zip>' . $data['cep'] . '</platformCommon:zip>
                        <platformCommon:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                            <!--Complemento-->
                            <platformCore:customField internalId="2159" scriptId="custrecord_sit_address_i_numero" xsi:type="platformCore:StringCustomFieldRef">
                                <platformCore:value>0</platformCore:value>
                            </platformCore:customField>

                            <!--Município-->
                            <platformCore:customField internalId="2154" scriptId="custrecord_o2g_address_l_mun" xsi:type="platformCore:SelectCustomFieldRef">
                                <platformCore:value internalId="' . $data['municipio'] . '" typeId="133">
                                    <platformCore:name>' . $data['municipio'] . '</platformCore:name>
                                </platformCore:value>
                            </platformCore:customField>
                            <!--Tipo Logradouro-->
                            <platformCore:customField internalId="2165" scriptId="custrecord_sit_address_l_tp_logr" xsi:type="platformCore:SelectCustomFieldRef">
                                <platformCore:value internalId="26" typeId="345">
                                    <platformCore:name>Rua</platformCore:name>
                                </platformCore:value>
                            </platformCore:customField>
                            <customField scriptId="custrecord_sit_address_t_bairro" internalId="2174" xsi:type="StringCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                                <value>' . $data['bairro'] . '</value>
                            </customField>
                        </platformCommon:customFieldList>
                    </cust:addressbookAddress>
                </cust:addressbook>
            </cust:addressbookList>
        </record>
        </upsert>
        </soap-env:Body>
        </soap-env:Envelope>';


        // Send the request.
        $response = $this->client->post(
            $this->WEBSERVICE_URL,
            $xml,
            [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'SOAPAction' => 'upsert',
                ]
            ]
        );

        $res_xml = $response->getStringBody();


        $xmlArray = Xml::toArray(Xml::build($res_xml));

        $status = $this->getStatus($xmlArray, 'upsertResponse', 'writeResponse');


        if ($status['@isSuccess'] == 'false') {
            throw new Exception(@$status['platformCore:statusDetail']['platformCore:message']);
        }

        return $xmlArray['Envelope']['soapenv:Body']['upsertResponse']['writeResponse']['baseRef']['@internalId'];
    }

    private function getDates($data)
    {

        $retorno = '';

        if (!empty($data['finalizacao_pedido_data'])) {
            $retorno .= '<!--DT_LiberacaoClinica-->
            <platformCore:customField scriptId="custcol_pslad_dt_liberacaoclinica" xsi:type="platformCore:DateCustomFieldRef">
                <platformCore:value>' . @$data['finalizacao_pedido_data'] . '</platformCore:value>
            </platformCore:customField>';
        }

        if (!empty($data['triagem_data'])) {
            $retorno .= '<!--DT_TriagemDistribuicao/ triagem-->
            <platformCore:customField scriptId="custcol_pslad_dt_triagemdistribuicao" xsi:type="platformCore:DateCustomFieldRef">
                <platformCore:value>' . @$data['triagem_data'] . '</platformCore:value>
            </platformCore:customField>';
        }
        if (!empty($data['diagnostico_data'])) {
            $retorno .= ' <!--DT_TriagemNTO/ envio da amostra-->
            <platformCore:customField scriptId="custcol_pslad_dt_triagemnto" xsi:type="platformCore:DateCustomFieldRef">
                <platformCore:value>' . @$data['diagnostico_data'] . '</platformCore:value>
            </platformCore:customField>';
        }


        return $retorno;
    }
    public function createPedido($data)
    {
        // $data = [
        //     'net_suite_id_cliente'
        //     'pedido_id',
        //     'paciente_nome',
        //     'cadastro_pedido',
        //     'valor_pedido',
        //     'finalizacao_pedido_data',
        //     'triagem_data',
        //     'diagnostico_data',
        // ];

        $xml = '<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
        ' . $this->generateHeader() . '
        <soap-env:Body>
        <upsert xmlns="urn:messages_2020_1.platform.webservices.netsuite.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" >
            <!--externalId deve ser informado o Id da ordem de serviço no sistema de origem -->
           <record xmlns:tranSales="urn:sales_2020_1.transactions.webservices.netsuite.com" xsi:type="tranSales:SalesOrder" externalId="' . $data['pedido_id'] . '">
               <tranSales:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                   <!--Identificador único no formato UUID-->
                   <platformCore:customField scriptId="custbody_sit_t_uid" xsi:type="platformCore:StringCustomFieldRef">
                       <platformCore:value>' . $this->guidv4() . '</platformCore:value>
                   </platformCore:customField>
                   <!-- Condição de pagamento-->
                   <platformCore:customField scriptId="custbody_sit_transaction_l_cond_pagto" xsi:type="platformCore:StringCustomFieldRef">
                       <platformCore:value>1</platformCore:value>
                   </platformCore:customField>
                   <!-- Manter os campos abaixo assim como default-->
                   <platformCore:customField scriptId="custbody_o2s_transaction_l_tip_doc_fis" xsi:type="platformCore:SelectCustomFieldRef">
                       <platformCore:value internalId="2" />
                   </platformCore:customField>
                   <platformCore:customField scriptId="custbody_o2s_transac_l_tipo_emitente" xsi:type="platformCore:SelectCustomFieldRef">
                       <platformCore:value>1</platformCore:value>
                   </platformCore:customField>
                   <!--FormRecebto-->
                   <platformCore:customField scriptId="custbody_pslad_payment_type" xsi:type="platformCore:SelectCustomFieldRef">
                       <platformCore:value internalId="1" />
                   </platformCore:customField>
                   <!--NM_AtendimentoApoiado/id pedido-->
                   <platformCore:customField scriptId="custbody_pslad_nm_atendimentoapoiado" xsi:type="platformCore:StringCustomFieldRef">
                       <platformCore:value>' . $data['pedido_id'] . '</platformCore:value>
                   </platformCore:customField>
                   <!--NoMe_Paciente-->
                   <platformCore:customField scriptId="custbody_pslad_nm_paciente" xsi:type="platformCore:StringCustomFieldRef">
                       <platformCore:value>' . $data['paciente_nome'] . '</platformCore:value>
                   </platformCore:customField>

                   <platformCore:customField scriptId="custbodycustomid_bio_codigo_item" xsi:type="StringCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                        <value>' . $data['pedido_id'] . '</value>
                    </platformCore:customField>

                    <platformCore:customField scriptId="custbodycustbodycustomid_bio_id_int" xsi:type="StringCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                    <value>' . $data['net_suite_id'] . '</value>
                    </platformCore:customField>

               </tranSales:customFieldList>
               <tranSales:orderStatus>' . $data['net_suite_status_pedido'] . '</tranSales:orderStatus>
               <!-- CD_Apoiado -->
               <tranSales:entity internalId="' . $data['net_suite_id_cliente'] . '" xsi:type="custSales:RecordRef" xmlns:custSales="urn:core_2020_1.platform.webservices.netsuite.com" />
               <!-- DT_Cadastro -->
               <tranSales:tranDate>' . $data['cadastro_pedido'] . '</tranSales:tranDate>
                <!--Representante de vendas -->
               <!-- <tranSales:salesRep internalId="CampoObservacao1Diagnosis" /> -->
               <!-- Lista dos items-->
               <tranSales:itemList replaceAll="true" xsi:type="tranSales:SalesOrderItemList">
                   <!-- Item -->
                   <tranSales:item xsi:type="tranSales:SalesOrderItem">
                       <!-- CD_Exame_MX -->
                       <tranSales:item externalId="COV19" xsi:type="tranItem:RecordRef" xmlns:tranItem="urn:core_2020_1.platform.webservices.netsuite.com"/>
                       <!-- Quantidade -->
                       <tranSales:quantity xsi:type="xsd:double">1</tranSales:quantity>
                       <!-- Valor -->
                       <tranSales:rate xsi:type="xsd:double">' . $data['valor_pedido'] . '</tranSales:rate>

                       <tranSales:customFieldList xmlns:platformCore="urn:core_2020_1.platform.webservices.netsuite.com">
                       <customField scriptId="custcol_pslad_cd_unidadeprodutiva" xsi:type="StringCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                            <value>IA COVID</value>
                        </customField>

                        <customField scriptId="custcol_pslad_st_pontoschecagem" xsi:type="SelectCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                        <value internalId="' . $data['status_item'] . '" />
                        </customField>

                        <customField scriptId="custcol_pslad_sq_exame_mx" xsi:type="StringCustomFieldRef" xmlns="urn:core_2020_1.platform.webservices.netsuite.com">
                        <value>1</value>
                        </customField>

                         ' . $this->getDates($data) . '
                         </tranSales:customFieldList>
                   </tranSales:item>
               </tranSales:itemList>
           </record>
       </upsert>
        </soap-env:Body>
      </soap-env:Envelope>';

        // Send the request.
        $response = $this->client->post(
            $this->WEBSERVICE_URL,
            $xml,
            [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'SOAPAction' => 'upsert',
                ]
            ]
        );

        $res_xml = $response->getStringBody();

        // debug($res_xml);
        // die;

        $xmlArray = Xml::toArray(Xml::build($res_xml));

        // debug($xmlArray);
        // die;
        $status = $this->getStatus($xmlArray, 'upsertResponse', 'writeResponse');

        if ($status['@isSuccess'] == 'false') {

            throw new Exception(@$status['platformCore:statusDetail']['platformCore:message']);
        }

        return $xmlArray['Envelope']['soapenv:Body']['upsertResponse']['writeResponse']['baseRef']['@internalId'];
    }

    public function executePedido($pedido_id, $net_suite_status_pedido = '_pendingApproval')
    {

        try {
            $pedido = $this->Pedidos->get($pedido_id, [
                'contain' => ['Anamneses.Pacientes', 'Exames', 'PedidoCroqui', 'Clientes']
            ]);

            //dados paciente
            if (!empty($pedido->anamnese->paciente)) {
                $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);
                $res = json_decode($resPaciente, true);
                $pedido->anamnese->paciente = new Paciente($res);
            }

            //verifica se o cliente ja existe
            if (empty($pedido->cliente->net_suite_id)) {
                return;
            }

            $data = [
                'net_suite_id_cliente' => $pedido->cliente->net_suite_id,
                'pedido_id' => 'CE_' . $pedido->id,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'cadastro_pedido' =>  $pedido->created->i18nFormat('yyyy-MM-dd') . 'T' . $pedido->created->i18nFormat('HH:mm:ss') . '.000000-03:00',
                'valor_pedido' => $pedido->valor_exame,
                'finalizacao_pedido_data' => '',
                'triagem_data' => '',
                'diagnostico_data' => '',
                'net_suite_status_pedido' => $net_suite_status_pedido,
                'net_suite_id' => @$pedido->net_suite_id,
                'status_item' => !empty(@$pedido->net_suite_id) ? 4 : 1,
            ];

            if (!empty($pedido->exame->created)) {
                $data['finalizacao_pedido_data'] = $pedido->exame->created->i18nFormat('yyyy-MM-dd') . 'T' . $pedido->exame->created->i18nFormat('HH:mm:ss') . '.000000-03:00';

                $data['diagnostico_data'] = $pedido->exame->created->i18nFormat('yyyy-MM-dd') . 'T' . $pedido->exame->created->i18nFormat('HH:mm:ss') . '.000000-03:00';
            }
            if (!empty($pedido->pedido_croqui->created)) {
                $data['triagem_data'] = $pedido->pedido_croqui->created->i18nFormat('yyyy-MM-dd') . 'T' . $pedido->pedido_croqui->created->i18nFormat('HH:mm:ss') . '.000000-03:00';
            }
            if (!empty($pedido->pedido_croqui->created)) {
                $data['triagem_data'] = $pedido->pedido_croqui->created->i18nFormat('yyyy-MM-dd') . 'T' . $pedido->pedido_croqui->created->i18nFormat('HH:mm:ss') . '.000000-03:00';
            }

            $external_id = $this->createPedido($data);

            $pedido->net_suite_id = $external_id;
            $this->Pedidos->save($pedido);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
