<?php

namespace App\Controller\Api;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Exception;
use Firebase\JWT\JWT;
use Cake\Routing\Router;
use App\Model\Entity\Paciente;
use Cake\Mailer\TransportFactory;
use Cake\Log\Log;
/**
 * Foo Controller
 *
 */
class PedidosController extends RestController
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
        $this->loadComponent('Email');
        $this->loadModel('LaudoJobs');
        $this->loadComponent('Email');

        $this->body = $this->Request->getBody();
        $this->API_ROOT = env('USER_ENDPOINT');
        $this->loadModel('Pedidos');
        $this->loadModel('Exames');

        $authorization = $this->request->getHeaderLine('Authorization');
        $authorization = explode(' ', $authorization);

        if (!empty($authorization[0])) {
            $token = $authorization[1];
            $config = include ROOT . DS . 'config' . DS . 'rest.php';
            $payload = JWT::decode($token, $config['Rest']['jwt']['key'], [$config['Rest']['jwt']['algorithm']]);
            $this->payload = $payload;
        }
    }

    public function dispatchEmails($pedido_id)
    {
        $job = $this->LaudoJobs->find('all', [
            'conditions' => ['pedido_id' => $pedido_id]
        ])->first();

        if (!empty($job)) {
            $pedido = $this->Pedidos->get($job->pedido_id, [
                'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras', 'Exames.Users'],
            ]);

            //buscando o paciente
            $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);

            $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);

            $res = json_decode($resPaciente, true);

            $pedido->anamnese->paciente = new Paciente($res);

            $nome_arquivo = $job->file;
            $dadosEmail = array();
            $dadosEmail['from'] = ['contato@testecovidexpress.com.br' => 'Covid Express'];
            $dadosEmail['to'] = $pedido->anamnese->paciente->email;
            $dadosEmail['cc'] = 'lucas.santos@dedtechsolutions.com.br';
            $dadosEmail['subject'] = 'Laudo';

            $dadosEmail['message'] = 'segue em anexo o laudo do seu exame';

            $nome_handle = explode('/', trim($nome_arquivo));

            $name = $nome_handle[count($nome_handle) - 1];

            $dadosEmail['attachments'] = [
                $name => [
                    'file' => $nome_arquivo,

                ]
            ];

            $this->Email->sendEmail($dadosEmail);
            $job->completed = 2;
            $this->LaudoJobs->save($job);
        }
        $this->set(compact('result'));
    }


    public function logTeste()
    {
        Log::write('debug', 'Something did not work');
    }
    public function dispatchEmailsCron()
    {

            $jobs = $this->LaudoJobs->find('all', [
                'conditions' => ['completed' => 1]
            ])->toList();

            if (!empty($jobs)) {

                foreach ($jobs as $key => $job) {

                    $pedido = $this->Pedidos->get($job->pedido_id, [
                        'contain' => ['Anamneses.Pacientes', 'EntradaExames', 'Vouchers', 'Exames.Amostras', 'Exames.Users'],
                    ]);

                    //buscando o paciente
                    $resPaciente = $this->PacientesData->getByHash($pedido->anamnese->paciente->hash);

                    $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);

                    $res = json_decode($resPaciente, true);

                    $pedido->anamnese->paciente = new Paciente($res);

                    $nome_arquivo = $job->file;
                    $dadosEmail = array();
                    $dadosEmail['from'] = ['contato@testecovidexpress.com.br' => 'Covid Express'];
                    $dadosEmail['to'] = $pedido->anamnese->paciente->email;
                    $dadosEmail['cc'] = 'lucas.santos@dedtechsolutions.com.br';
                    $dadosEmail['subject'] = 'Laudo';

                    $dadosEmail['message'] = 'segue em anexo o laudo do seu exame';

                    $nome_handle = explode('/', trim($nome_arquivo));
                    $name = $nome_handle[count($nome_handle) - 1];

                    $dadosEmail['attachments'] = [
                        $name => [
                            'file' => $nome_arquivo,

                        ]
                    ];

                    $this->Email->sendEmail($dadosEmail);

                    $job->completed = 2;
                    $job = $this->LaudoJobs->save($job);

                }

            }
            $this->set(compact('result'));

    }

    public function index()
    {
        $result = [];

        $body = $this->body;
        $token = $this->token;

        $payload = $this->payload;


        $pedidos = $this->Pedidos->find('all', [
            'contain' => ['Exames', 'Anamneses.Pacientes', 'Clientes', 'EntradaExames'],
            'conditions' => ['Anamneses.paciente_id' => $payload->id]
        ])->toList();

        $handle = [];

        foreach ($pedidos as $key => $pedido) {

            if (!empty($pedido->exame)) {
                $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);
            }

            $handle[] = [
                'clinica_nome' => $pedido->cliente->nome_fantasia ?? '-',
                'exame_nome' => $pedido->entrada_exame->nome ?? '-',
                'id' => $pedido['id'],
                'codigo' => $pedido['codigo_pedido'],
                'status' => $pedido['status'],
                'amostra_tipo' => $pedido['exame']['amostra_tipo'] ?? '-',
                'resultado' => $pedido['exame']['resultado'] ?? '-',
                'url_exame' => Router::url('/admin/pedidos/laudoWeb/' . $pedido['id'], true)
            ];
        }

        $result['pedidos'] = $handle;

        $this->set(compact('result'));
    }
}
