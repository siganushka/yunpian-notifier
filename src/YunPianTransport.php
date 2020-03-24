<?php

namespace Siganushka\Notifier\Bridge\YunPian;

use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YunPianTransport extends AbstractTransport
{
    const ENDPOINT = 'https://sms.yunpian.com/v2/sms/single_send.json';

    private $apikey;

    public function __construct(string $apikey, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->apikey = $apikey;

        parent::__construct($client, $dispatcher);
    }

    protected function doSend(MessageInterface $message): void
    {
        if (!$message instanceof SmsMessage) {
            throw new LogicException(sprintf('The "%s" transport only supports instances of "%s" (instance of "%s" given).', __CLASS__, SmsMessage::class, \get_class($message)));
        }

        $response = $this->client->request('POST', self::ENDPOINT, [
            'body' => [
                'apikey' => $this->apikey,
                'mobile' => $message->getPhone(),
                'text' => $message->getSubject(),
            ],
        ]);

        try {
            $content = $response->getContent();
        } catch (\Throwable $th) {
            throw new TransportException(sprintf('Unable to send sms (%s: %s)', $th->getCode(), $th->getMessage()), $response);
        }

        $data = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \UnexpectedValueException(json_last_error_msg());
        }

        if (isset($data['code']) && 0 != $data['code']) {
            throw new TransportException(sprintf('Unable to send sms (%s: %s)', $data['code'], $data['msg']), $response);
        }
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof SmsMessage;
    }

    public function __toString(): string
    {
        return sprintf('yunpian://%s?apikey=%s', $this->getEndpoint(), $this->apikey);
    }
}
