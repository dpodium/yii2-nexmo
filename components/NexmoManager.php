<?php

namespace dpodium\yii2\Nexmo;

class NexmoManager
{
    public $config = [];
    public $test_mode = true;
    public $proxy = null;

    public function sendSms($to, $from, $text, $client_ref = null) {
        try{
            $text = new \Nexmo\Message\Text($to, $from, $text);
            $text->setClientRef($client_ref)
                ->setClass(\Nexmo\Message\Text::CLASS_FLASH);
            $client = $this->initClient();
            $client->message()->send($text);
        } catch (Nexmo\Client\Exception\Request $e) {
            //can still get the API response
            $text     = $e->getEntity();
            $request  = $text->getRequest(); //PSR-7 Request Object
            $response = $text->getResponse(); //PSR-7 Response Object
            $data     = $text->getResponseData(); //parsed response object
            $code     = $e->getCode(); //nexmo error code
            error_log($e->getMessage()); //nexmo error message
        }
    }

    protected function initClient()
    {
        return new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic($this->config['api.key'], $this->config['api.secret']));
    }
}