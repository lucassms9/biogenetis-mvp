<?php

namespace App\Controller\Barramento;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Cake\I18n\Number;
use Exception;
use Firebase\JWT\JWT;
use App\Model\Entity\Paciente;

/**
 * Foo Controller
 *
 */
class OrdersController extends RestController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Request');
        $this->loadComponent('PacientesData');
        $this->loadComponent('ExamesData');
        $this->loadModel('Pacientes');

        $this->body = $this->Request->getBody();
        $this->API_ROOT = env('USER_ENDPOINT');
        $this->loadModel('Pacientes');
        $this->loadModel('AuthIntegrations');
        $this->loadModel('EntradaExames');
        $this->loadModel('Anamneses');
        $this->loadModel('Clientes');
        $this->loadModel('Pedidos');

        $authorization = $this->request->getHeaderLine('Authorization');

        $authorization = explode(' ', $authorization);
        $token = $authorization[1];
        $config = include ROOT . DS . 'config' . DS . 'rest.php';
        $payload = JWT::decode($token, $config['Rest']['jwt']['key'], [$config['Rest']['jwt']['algorithm']]);

        $this->payload = $payload;
    }

    /*
        campos novos:
         - pedidos
         CodigoPrioridade
         DataHoraDUM
         DescricaoDadosClinicos
         DescricaoMedicamentos
         DescricaoRegiaoColeta
         MaterialApoiado
         CodigoConselho
         CodigoConselhoSolicitante
         CodigoUFConselhoSolicitante
         NomeSolicitante
         NumeroAtendimentoApoiado
         PostoColeta
         UsoApoiado

         - paciente
            NumeroCartaoNacionalSaude
            PesoPaciente
            AlturaPaciente
    */

    public function create()
    {
        $result = [];

        $body = $this->body;
        $pedido = $body['ct_Atendimento_v1']['ct_Pedido_v1'];
        $exame = $body['ct_Atendimento_v1']['ct_Pedido_v1']['ListaProcedimento'];

        //verificar se o paciente existe createOrUpdate
        //buscar exame selecionado
        //salvar dados do pedido
        $resPaciente  = $this->PacientesData->getCheckCPFOrEmail(
            $pedido['PacienteApoiado'][0]['NumeroCPF'],
            ''
        );

        $mergeItems = array_merge($pedido['PacienteApoiado'][0], [
            'AlturaPaciente' => $pedido['AlturaPaciente'],
            'PesoPaciente' => $pedido['PesoPaciente'],
        ]);
        //criar paciente
        if (!$resPaciente) {
            $paciente = $this->createPaciente($mergeItems);
        } else {
            $res = json_decode($resPaciente);

            $paciente = $this->Pacientes->find('all', [
                'conditions' => ['hash' => $res->hash]
            ])->first();
        }
        //fim criacao pedido

        //buscar exame
        $exameFind = $this->EntradaExames->find('all', [
            'conditions' => ['id' => $exame[0]['CodigoExameHSF']]
        ])->first();

        if (!$exameFind) {
            throw new Exception('exame inválido', 400);
        }

        $anamnese = $this->Anamneses->newEntity();
        $anamnese = $this->Anamneses->patchEntity($anamnese, [
            'paciente_id' => $paciente->id
        ]);
        $anamnese = $this->Anamneses->save($anamnese);

        $payload = $this->payload;

        $cliente = $this->Clientes->find('all', [
            'conditions' => ['id' => $payload->cliente_id]
        ])->first();

        if (!$cliente) {
            throw new Exception('cliente inválido', 400);
        }


        //criacao de pedido
        $dados_pedido = [
            'anamnese_id' => $anamnese->id,
            'cliente_id' => $cliente->id,
            'tipo_pagamento' => $cliente->tipo_cobranca,
            'created_by' => $cliente->id,
            'entrada_exame_id' => $exameFind->id,
            'status' => 'EmAtendimento',
            'codigo_prioridade' => $pedido['CodigoPrioridade'],
            'data_hora_DUM' => $pedido['DataHoraDUM'],
            'descricao_dados_clinicos' => $pedido['DescricaoDadosClinicos'],
            'descricao_medicamentos' => $pedido['DescricaoMedicamentos'],

            'descricao_regiao_coleta' => $pedido['ListaProcedimento'][0]['DescricaoRegiaoColeta'],
            'material_apoiado' => $pedido['ListaProcedimento'][0]['MaterialApoiado'],

            'codigo_conselho' => $pedido['ListaSolicitante'][0]['CodigoConselho'],
            'codigo_conselho_solicitante' => $pedido['ListaSolicitante'][0]['CodigoConselhoSolicitante'],
            'codigo_UF_conselho_solicitante' => $pedido['ListaSolicitante'][0]['CodigoUFConselhoSolicitante'],
            'nome_colicitante' => $pedido['ListaSolicitante'][0]['NomeSolicitante'],

            'numero_atendimento_apoiado' => $pedido['NumeroAtendimentoApoiado'],
            'posto_coleta' => $pedido['PostoColeta'],
            'uso_apoiado' => $pedido['UsoApoiado'],
        ];


        $pedidoSave = $this->Pedidos->newEntity();
        $pedidoSave = $this->Pedidos->patchEntity($pedidoSave, $dados_pedido);


        $pedidoSave = $this->Pedidos->save($pedidoSave);


        $result['NumeroAtendimentoApoiado'] = $pedido['NumeroAtendimentoApoiado'];

        $result['Status'] = $pedidoSave->status;
        $result['NumeroPedido'] = $pedidoSave->codigo_pedido;
        $result['Amostras'] = [
            'CodigoInstrumento' => '',
            'ContadorAmostra' => '',
            'Exames' => '',
            'FlagAmostraMae' => '',
            'GrupoInterface' => '',
            'Material' => '',
            'MeioColeta' => '',
            'NomePaciente' => '',
            'NumeroAmostra' => '',
            'Origem' => '',
            'Prioridade' => '',
            'RegiaoColeta' => '',
            'RGPacienteHSF' => '',
            'TextoAmostraMae' => '',
            'TipoCodigoBarras' => '',
            'Volume' => ''
        ];

        $this->set(compact('result'));
    }

    public function list()
    {
        /*
         "AlturaPaciente":"",
        "DataNascimento":"datatime",
        "ListaResultadoProcedimentos":[
            {
            "CodigoExameHSF":"string",
            "DataHoraLiberacaoClinica":"datatime",
            "DescricaoExameApoio":"string",
            "DescricaoMaterialApoio":"string",
            "DescricaoMetodologia":"string",
            "DescricaoRegiaoColeta":"string",
            "IdentificacaoExameApoiado":"string",
            }
        ],
        "NomePaciente":"string",
        "NumeroAtendimentoApoiado":"string",
        "NumeroPedido":"string",
        "NumeroCPF":"string",
        "PesoPaciente":"string",
        "RGPacienteHSF":"string",
        "SexoPaciente":"string",
        "UsoApoiado":"string",
        */
        $retorno = [];

        $conditions = [
            'Pedidos.cliente_id' => $this->payload->cliente_id
        ];

        $q = $this->request->getQuery();

        if (!empty($q['NumeroAtendimentoApoiado'])) {
            $conditions['numero_atendimento_apoiado'] = $q['NumeroAtendimentoApoiado'];
        }

        $result = [];

        $body = $this->body;

        $pedidos = $this->Pedidos->find('all', [
            'contain' => ['Anamneses.Pacientes', 'Exames.Amostras', 'Exames.Users'],
            'conditions' => $conditions
        ]);

        foreach ($pedidos as $key => $pedido) {

            $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);
            $res = json_decode($resPaciente, true);
            if (!empty($pedido->exame)) {
                $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);
            }


            $pedido->anamnese->paciente = new Paciente($res);

            $retorno[] = [
                "AlturaPaciente" => $pedido->anamnese->paciente->altura_paciente,
                "DataNascimento" => $pedido->anamnese->paciente->data_nascimento,
                "ListaResultadoProcedimentos" => [
                    "CodigoExameHSF" => "",
                    "DataHoraLiberacaoClinica" => "datatime",
                    "DescricaoExameApoio" => "string",
                    "DescricaoMaterialApoio" => "string",
                    "DescricaoMetodologia" => "string",
                    "DescricaoRegiaoColeta" => "string",
                    "IdentificacaoExameApoiado" => "string",
                ],
                "NomePaciente" => $pedido->anamnese->paciente->nome,
                "NumeroAtendimentoApoiado" => $pedido->numero_atendimento_apoiado,
                "NumeroPedido" => $pedido->codigo_pedido,
                "NumeroCPF" => $pedido->anamnese->paciente->cpf,
                "PesoPaciente" => $pedido->anamnese->paciente->peso_paciente,
                "RGPacienteHSF" => $pedido->anamnese->paciente->rg,
                "SexoPaciente" => $pedido->anamnese->paciente->sexo,
                "UsoApoiado" => $pedido->uso_apoiado,
            ];
        }



        $this->set(compact('retorno'));
    }


    private function createPaciente($data)
    {

        $dataSave = [
            'nome' => $data['NomePaciente'],
            'cpf' => $data['NumeroCPF'],
            'rg' => $data['RGPacienteApoiado'],
            'email' => '',
            'celular' => '',
            'telefone' => '',
            'profissao' => '',
            'sexo' => $data['SexoPaciente'],
            'endereco' => '',
            'bairro' => '',
            'data_nascimento' => $data['DataNascimento'],
            'cep' => '',
            'uf' => '',
            'cidade' => '',
            'nome_da_mae' => '',
            'nacionalidade' => 'BRASILEIRA',
            'pais_residencia' => 'BRASIL',
            'senha' => '',
            'numero_cartao_nacional_saude' => $data['NumeroCartaoNacionalSaude'],
            'peso_paciente' => $data['PesoPaciente'],
            'altura_paciente' => $data['AlturaPaciente'],
        ];

        $json = json_encode($dataSave);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/create', $json, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($json)]
        ]);

        $jst_r  = json_decode($response->getStringBody());

        $data_user['hash'] = $jst_r->hash;

        $paciente = $this->Pacientes->newEntity();
        $paciente = $this->Pacientes->patchEntity($paciente, $data_user);
        $paciente = $this->Pacientes->save($paciente);

        return $paciente;
    }
}
