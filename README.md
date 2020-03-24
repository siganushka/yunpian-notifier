# Symfony YunPian Notifier Bridge.

适用于 [symfony/notifier](https://symfony.com/doc/current/notifier.html) 消息组件的 [云片](https://www.yunpian.com/official/document/sms/zh_cn/domestic_single_send) 短信传输。

> 实验性项目，请勿在生产环境中使用。

### 安装

```bash
$ composer require siganushka/yunpian-notifier:dev-master
```

### 配置

```bash
# .env

YUNPIAN_DSN=yunpian://default?apikey=YOUR_APIKEY
```

```yaml
# ./config/packages/notifier.yaml

framework:
    notifier:
        texter_transports:
            yunpian: '%env(YUNPIAN_DSN)%'
```

```yaml
# ./config/services.yaml

Siganushka\Notifier\Bridge\YunPian\YunPianTransportFactory:
    tags: [ texter.transport_factory ]
```

### 发送短信

```php
namespace App\Controller;

use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class FooController
{
    /**
     * @Route("/foo")
     */
    public function foo(TexterInterface $texter)
    {
        $message = new SmsMessage('18611111111', '【签名】测试短信。。。');

        try {
            $texter->send($message);
        } catch (TransportException $th) {
            // 发送失败
        }

        // ...
    }
}
```
