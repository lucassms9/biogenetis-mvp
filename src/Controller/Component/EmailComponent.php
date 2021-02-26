<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Core\Configure;

class EmailComponent extends Component
{
    public function initialize(array $config)
    {
        parent::initialize($config);


        $host = Configure::read('EmailSend.smtp_host');
        $port = Configure::read('EmailSend.smtp_port');
        $usern = Configure::read('EmailSend.smtp_user');
        $password = Configure::read('EmailSend.smtp_password');

        // debug($host);
        // debug($port);
        // debug($usern);
        // debug($password);
        // die;
        TransportFactory::drop('covid_express');

        TransportFactory::setConfig('covid_express', [
          'host' => $host,
          'port' => $port,
          'username' => $usern,
          'password' => $password,
          'className' => 'Smtp',
          'tls' => true
        ]);

        $this->email = new Email();
        $this->email->setTransport('covid_express');
        $this->API_ROOT = env('EXAME_ENDPOINT');
    }

    public function sendEmail($dados)
    {
        try {



            // TransportFactory::setConfig('mailtrap', [
            //   'host' => $host,
            //   'port' => $port,
            //   'username' => $usern,
            //   'password' => $password,
            //   'className' => 'Smtp'
            // ]);


            $this->email->setFrom($dados['from']);
            $this->email->setTo($dados['to']);

            if(isset($dados['replyTo']) && $dados['replyTo'] != ''){
                $this->email->replyTo($dados['replyTo']);
            }

            if(isset($dados['cc']) && $dados['cc'] != ''){
                $this->email->cc($dados['cc']);
            }

            if(isset($dados['html_format']) && $dados['html_format']){
                $this->email->emailFormat('html');
            }

            if(isset($dados['attachments']) && $dados['attachments']){
                $this->email->setAttachments($dados['attachments']);
            }

            $this->email->setSubject($dados['subject']);
            $this->email->send($dados['message']);

            if($this->email){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
        }
    }


}
