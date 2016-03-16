<?php

namespace PharmIT\Push\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;

class ApplePushService extends AbstractPushService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @inheritdoc
     */
    public function loadConfiguration($config)
    {
        if (!isset($config['environment']) || !in_array($config['environment'], ['development', 'production'])) {
            return false;
        }

        $host = ($config['environment'] == 'development') ? 'https://api.development.push.apple.com' : 'https://api.push.apple.com';


        if (!isset($config['certificate']) || !is_file(base_path() . '/' . $config['certificate'])) {
            return false;
        }

        $cert = base_path() . '/' . $config['certificate'];
        if (isset($config['passphrase'])) {
            $cert = [$cert, $config['passphrase']];
        }

        if (!isset($config['topic'])) {
            return false;
        }

        $this->client = new Client([
            'version'  => 2,.0,
            'cert'     => $cert,
            'base_uri' => $host,
            'headers'  => [
                'apns-topic' => $config['topic'],
            ],
//            'default' => [
                'curl' => array(
                    'CURLOPT_SSLVERSION' => 'CURL_SSLVERSION_TLSv1_2',
                )
//            ]
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $body = [
            'aps' => [],
        ];

        if ($this->messageData !== null) {
            $body['aps'] = $this->messageData;
        }

        if ($this->messageText !== null) {
            $body['aps']['alert'] = $this->messageText;
            if (!isset($body['aps']['sound'])) {
                $body['aps']['sound'] = 'default';
            }
            if (!isset($body['aps']['badge'])) {
                $body['aps']['badge'] = 3;
            }
        }

//        var_dump($body);
        $bodyData = json_encode($body);

        $ok = [];
        $promises = [];

        foreach ($this->recipients as $recipient) {
            $url = sprintf('/3/device/%s', $recipient);
            $promises[] = $this->client->postAsync($url, [
                'body' => $bodyData,
            ])->then(function (ResponseInterface $response) use (&$ok, $recipient) {
                if ($response->getStatusCode() == 200) {
                    // Set to OK if we received a 200
                    $ok[] = $recipient;
                } else {
                    $this->failedRecipients[] = $recipient;
                }
            }, function () use ($recipient) {
                $this->failedRecipients[] = $recipient;
            });
        }

        // Wait for all requests to complete
        Promise\unwrap($promises);

        return $ok;
    }
}
