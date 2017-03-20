<?php

namespace dpodium\yii2\Nexmo;

class NexmoManager
{
    public $config = [];
    public $proxy = null;

    public function sendSms($to, $from, $text, $client_ref = null) {
        try{
            $text = new \Nexmo\Message\Text($to, $from, $text);
            $text->setClientRef($client_ref)
                ->setClass(\Nexmo\Message\Text::CLASS_FLASH);
            $client = $this->initClient();
            $client->message()->send($text);
        } catch (\Nexmo\Client\Exception\Request $e) {
            //can still get the API response
            $text     = $e->getEntity();
            $request  = $text->getRequest(); //PSR-7 Request Object
            $response = $text->getResponse(); //PSR-7 Response Object
            $data     = $text->getResponseData(); //parsed response object
            $code     = $e->getCode(); //nexmo error code
            error_log($e->getMessage()); //nexmo error message
        }
    }

    /*
     * Inbound messages are sent to your application as a webhook, and the client library provides a way to create an inbound message object from a webhook:
     */
    public function receiveMessage() {
        $inbound = \Nexmo\Message\InboundMessage::createFromGlobals();
        if($inbound->isValid()){
            error_log($inbound->getBody());
        } else {
            error_log('invalid message');
        }
    }

    /*
     * You can retrieve a message log from the API using the ID of the message:
     */
    public function fetchMessage($msgId) {
        $client = $this->initClient();
        $message = new \Nexmo\Message\InboundMessage($msgId);
        $client->message()->search($message);
        echo "The body of the message was: " . $message->getBody();
    }

    /*
     * Nexmo's Verify API makes it easy to prove that a user has provided their own phone number during signup, or implement second factor authentication during signin.
     * Return : requestId
     */
    public function initVerification($number, $brand, $additional = []) {
        $client = $this->initClient();
        $verification = new \Nexmo\Verify\Verification($number, $brand, $additional);
        return $client->verify()->start($verification);
    }

    /*
     * To cancel an in-progress verification, or to trigger the next attempt to send the confirmation code, you can pass either an exsisting verification object to the client library, or simply use a request ID:
     */
    public function triggerVerification($requestId) {
        $client = $this->initClient();
        return $client->verify()->trigger($requestId);
    }

    public function cancelVerification($requestId) {
        $client = $this->initClient();
        $verification = new \Nexmo\Verify\Verification($requestId);
        return $client->verify()->cancel($verification);
    }

    /*
     * In the same way, checking a verification requires the code the user provided, and an exiting verification object:
     */
    public function checkVerification($requestId, $code) {
        $client = $this->initClient();
        $verification = new \Nexmo\Verify\Verification($requestId);
        return $client->verify()->check($verification, $code);
    }

    /*
     * You can check the status of a verification, or access the results of past verifications using either an exsisting verification object, or a request ID.
     * The verification object will then provide a rich interface:
     */
    public function searchVerification($requestId) {
        $client = $this->initClient();
        $verification = new \Nexmo\Verify\Verification($requestId);
        return $client->verify()->search($verification);
    }

    protected function initClient()
    {
        $http_client = isset($this->proxy) ? \Http\Adapter\Guzzle6\Client::createWithConfig([
                'proxy' => $this->proxy['host'] . ':' . $this->proxy['port'],
            ]) : null;
        return new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic($this->config['api.key'], $this->config['api.secret']), array(), $http_client);
    }
}