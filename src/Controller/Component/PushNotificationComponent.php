<?php

namespace App\Controller\Component;

use Cake\Controller\Component;


class PushNotificationComponent extends Component
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->EXPO = \ExponentPhpSDK\Expo::normalSetup();
    }

    public function subscribe($channelName, $recipient)
    {
        // Subscribe the recipient to the server
        $this->EXPO->subscribe($channelName, $recipient);
    }

    /**
     * @param (array) $data
     *                  'paciente_id' => '',
                        'title' => '',
                        'body' => ''
     */
    public function send($data)
    {
        // Build the notification data
        $notification = [
            'title' => $data['title'],
            'body' => $data['body']
        ];

        // Notify an interest with a notification
        $this->EXPO->notify([$data['paciente_id']], $notification);
    }
}
