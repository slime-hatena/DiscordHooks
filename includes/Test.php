<?php
/// 内容をそのまま出力します。
/// Param: 出力する内容

include __DIR__.'/../vendor/autoload.php';
use \DiscordWebhooks\Client as DiscordWebhook;

$discord = new DiscordWebhook($value->WebhookURL);
$discord->name($value->UserName);
$discord->avatar($value->UserAvatar);
$discord->send($value->Param);