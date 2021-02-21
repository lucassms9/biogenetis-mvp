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

            $host = Configure::read('EmailSend.smtp_host');
            $port = Configure::read('EmailSend.smtp_port');
            $usern = Configure::read('EmailSend.smtp_user');
            $password = Configure::read('EmailSend.smtp_password');
            TransportFactory::drop('covid_express');
            TransportFactory::setConfig('covid_express', [
              'host' => $host,
              'port' => $port,
              'username' => $usern,
              'password' => $password,
              'className' => 'Smtp'
            ]);

            $email = new Email();
            $email->setTransport('covid_express');
            $email->setFrom($dados['from']);
            $email->setTo($dados['to']);

            if(isset($dados['replyTo']) && $dados['replyTo'] != ''){
                $email->replyTo($dados['replyTo']);
            }

            if(isset($dados['cc']) && $dados['cc'] != ''){
                $email->cc($dados['cc']);
            }

            if(isset($dados['html_format']) && $dados['html_format']){
                $email->emailFormat('html');
            }

            if(isset($dados['attachments']) && $dados['attachments']){
                $email->setAttachments($dados['attachments']);
            }

            $email->setSubject($dados['subject']);
            $email->send($dados['message']);

            if($email){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
        }
    }


}
