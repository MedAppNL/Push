<?php

namespace PharmIT\Push;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;

class GooglePushService extends AbstractPushService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @inheritdoc
     */
    public function loadConfiguration()
    {
        $config = config('push.google');

        $host = 'https://gcm-http.googleapis.com';

        if (!isset($config['apikey'])) {
            return false;
        }

        $this->client = new Client([
            'base_uri' => $host,
            'headers' => [
                'Authorization' => sprintf('key=%s', $config['apikey']),
                'Content-Type' => 'application/json',
            ],
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $body = [
            'data' => [],
        ];

        if ($this->messageData !== null) {
            $body['data'] = $this->messageData;
        }

        if ($this->messageText !== null) {
            $body['data']['message'] = $this->messageText;
        }

        $ok = [];
        $promises = [];

        $recipients_chunked = array_chunk($this->recipients, 1000);
        foreach ($recipients_chunked as $recipients_part) {
            $body['registration_ids'] = $recipients_part;
            $promises[] = $this->client->postAsync('/gcm/send', [
                'body' => json_encode($body),
            ])->then(function(ResponseInterface $response) use (&$ok, $recipients_part) {
                if ($response->getStatusCode() == 200) {
                    // Set to OK if we received a 200
                    $contents = json_decode($response->getBody()->getContents(), true);
                    $results = $contents['results'];
                    foreach ($recipients_part as $idx => $recipient) {
                        if (isset($results[$idx]['message_id']) && !isset($results[$idx]['error'])) {
                            $ok[] = $recipient;
                        } else {
                            $this->failedRecipients[] = $recipient;
                        }
                    }
                }
            }, function() use ($recipients_part) {
                foreach ($recipients_part as $idx => $recipient) {
                    $this->failedRecipients[] = $recipient;
                }
            });
        }

        // Wait for all requests to complete
        Promise\unwrap($promises);

        return $ok;
    }
}