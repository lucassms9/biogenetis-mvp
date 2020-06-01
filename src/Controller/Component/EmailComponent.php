<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Core\Configure;

class EmailComponent extends Component
{
    public function sendEmail($dados)
    {
        try {
             
            $host = Configure::read('EmailSend.smtp_host');
            $port = Configure::read('EmailSend.smtp_port');
            $usern = Configure::read('EmailSend.smtp_user');
            $password = Configure::read('EmailSend.smtp_password');
            
            // Email::configTransport('gmail', [
            //     'host' => $host,
            //     'port' => $port,
            //     'username' => $usern, // valid email account
            //     'password' => $password, // valid account password 
            //     'className' => 'Smtp',
            //     'log' => true,
            //     'context' => [
            //         'ssl' => [
            //             'verify_peer' => false,
            //             'verify_peer_name' => false,
            //             'allow_self_signed' => true
            //         ]
            //     ]
            // ]);

            TransportFactory::setConfig('mailtrap', [
              'host' => $host,
              'port' => $port,
              'username' => $usern,
              'password' => $password,
              'className' => 'Smtp'
            ]);

            $email = new Email();
            $email->transport('mailtrap');
            $email->from($dados['from']);
            $email->to($dados['to']);
             
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
                $email->attachments($dados['attachments']);
            }

            $email->subject($dados['subject']); 
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