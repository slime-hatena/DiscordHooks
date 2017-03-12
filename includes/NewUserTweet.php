<?php
/// 指定ユーザーの新しいツイートを取得します。
/// Param: 取得したいユーザー名
/// Avatar: 画像。 空欄ならそのユーザーのアイコンを取得
/// Name: 表示名。 空欄ならそのユーザーの名前+@スクリーンネームを取得

include __DIR__.'/../vendor/autoload.php';
use \DiscordWebhooks\Client as DiscordWebhook;

// Cowitterロード
use mpyw\Co\Co;
use mpyw\Co\CURLException;
use mpyw\Cowitter\Client;
use mpyw\Cowitter\HttpException;

// Twitter情報ロード
include_once dirname ( __FILE__ ) . '/../settings/NewUserTweet/option.php';
include_once $TwitterInfoPath;

// Twitterに接続
$client = new Client([$consumer_key, $consumer_secret,$access_token,$access_token_secret]);
$client = $client->withOptions([CURLOPT_CAINFO => __DIR__ . '/../vendor/cacert.pem']);

$json = file_get_contents(dirname ( __FILE__ ) . '/../settings/NewUserTweet/userdata.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,false);

// 表記ゆれを防ぐため全て小文字に
$screenName = mb_strtolower($value->Param);


// 今までにないユーザーならjsonに追加
if(!isset($arr->{$screenName})){
    $arr->{$screenName} = new stdClass;
    $arr->{$screenName}->since = "0";
}


// ツイート取得
try{
    if($arr->{$screenName}->since == ("0")){
        $statuses = $client->get('statuses/user_timeline', ['screen_name' => $screenName]);
    }else{
        $statuses = $client->get('statuses/user_timeline', ['screen_name' => $screenName , 'since_id' => $arr->{$screenName}->since]);
    }
}catch(HttpException $e){
    echo "<pre>" . $e . "</pre>";
    echo "&nbsp;&nbsp;@" . $screenName . " is not found...<br>";
}

if(count($statuses) == 0){
    echo "&nbsp;&nbsp;更新なし";
}else{
    $arr->{$screenName}->since = $statuses[0]->id_str;
}

// jsonを更新
$arr = json_encode($arr,JSON_PRETTY_PRINT);
file_put_contents(dirname ( __FILE__ ) . '/../settings/NewUserTweet/userdata.json' , $arr);

// discordへ出力
$discord = new DiscordWebhook($value->WebhookURL);
foreach ($statuses as $key => $info) {
    echo $key . ": " . mb_strimwidth($info->text, 0, 120, "...") . "<br>";
    $discord->name($value->UserName != "" ? $value->UserName : $info->user->name);
    $discord->avatar($value->UserAvatar != "" ? $value->UserAvatar : $info->user->profile_image_url);
    
    // md記法はエスケープする ` * _
    $postText = str_replace("_", "\_", str_replace("*", "\*", str_replace("`", "\`", $info->text . "\n")));
    $discord->send($postText);
    
    if($key >= $PostLimit - 1)  break;
ob_flush();
flush();
sleep(1);
}