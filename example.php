<?php

// Example config to use
$config = [
    'push.apple' => [
        'environment' => 'development',
        'certificate' => __DIR__ . '/yourkey.pem',
        'passphrase' => 'yourcertpassphrase',
        'topic' => 'your.aps.topic',
    ],
    'push.google' => [
        'apikey' => 'yourgcmapikey',
    ],
];

$applePush = \PharmIT\Push\PushServiceFactory::getPushService('apple');

if (!$applePush) {
    fwrite(STDERR, "Something went wrong\n");
    exit;
}

$results = $applePush
    ->setMessageText('Some message')
    // or setMessageData with ['alert' => '...']
    ->addRecipient('enterpushidhere')
    ->addRecipients(['youcanalsoenter', 'arrays'])
    ->send();

var_dump($results);
var_dump($applePush->getFailedRecipients());

$googlePush = \PharmIT\Push\PushServiceFactory::getPushService('google');

if (!$googlePush) {
    fwrite(STDERR, "Something went wrong\n");
    exit;
}

$results = $googlePush
    ->setMessageText('Some message')
    // or setMessageData with ['message' => '...']
    ->addRecipient('enterregistrationidhere')
    ->addRecipients(['youcanalsoenter', 'arrays'])
    ->send();

var_dump($results);
var_dump($googlePush->getFailedRecipients());