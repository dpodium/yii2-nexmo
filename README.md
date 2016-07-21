Yii2 Nexmo
==========
Nexmo yii2 wrapper. Enable nexmo services in yii2 application.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require dpodium/yii2-nexmo "*"
```

or add

```
"dpodium/yii2-nexmo": "*"
```

to the require section of your `composer.json` file.

Component Setup
-----
Once the extension is installed, simply modify your application configuration as follows:
```php
return [
    'components' => [
    ...
        'nexmo' => [
                   'class' => dpodium\yii2\Nexmo\NexmoManager::class,
                   'config' => [
                                   'api.key' => API_KEY, //from nexmo
                                   'api.secret' => API_SECRET, //from nexmo
                               ],
                   //leave blank if not applicable
                   'proxy' => [
                                   'host' => HOST,
                                   'port' => PORT,
                               ],
               ],
        ...
    ],
    ...
];
```

Usage
_____
    Sending message
    Yii::$app->nexmo->sendSms(TO, FROM, 'Test Nexmo Message');

    Receiving A Message
    Yii::$app->nexmo->receiveMessage();

    Fetching A Message
    Yii::$app->nexmo->fetchMessage(MESSAGE_ID);

    Starting a Verification
    Yii::$app->nexmo->initVerification(NUMBER, BRAND);

    Controlling a Verification
    Trigger
    Yii::$app->nexmo->triggerVerification(REQUEST_ID);
    Cancel
    Yii::$app->nexmo->cancelVerification(REQUEST_ID);

    Checking A Verification
    Yii::$app->nexmo->checkVerification(REQUEST_ID, CODE);

    Searching For A Verification
    Yii::$app->nexmo->searchVerification(REQUEST_ID);
