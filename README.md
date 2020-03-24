# YunPian Notifier Bridge.

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
