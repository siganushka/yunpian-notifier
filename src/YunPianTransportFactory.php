<?php

namespace Siganushka\Notifier\Bridge\YunPian;

use Symfony\Component\Notifier\Exception\IncompleteDsnException;
use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;

class YunPianTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        if ('yunpian' !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, 'yunpian', $this->getSupportedSchemes());
        }

        if (null === $apikey = $dsn->getOption('apikey')) {
            throw new IncompleteDsnException('Missing "apikey" for notifier yunpian.');
        }

        $transport = new YunPianTransport($apikey, $this->client, $this->dispatcher);
        $transport->setHost($dsn->getHost());
        $transport->setPort($dsn->getPort());

        return $transport;
    }

    protected function getSupportedSchemes(): array
    {
        return ['yunpian'];
    }
}
