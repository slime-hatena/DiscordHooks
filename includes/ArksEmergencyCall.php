<?php
/// @pso2_emg_hourさんの情報を取得して指定したshipの緊急情報を取得します。
/// Param: int ship
/// Avatar: 表示画像
/// Name: ユーザー名。
date_default_timezone_set('Asia/Tokyo');

include __DIR__.'/../vendor/autoload.php';
use \DiscordWebhooks\Client as DiscordWebhook;

// Cowitterロード
use mpyw\Co\Co;
use mpyw\Co\CURLException;
use mpyw\Cowitter\Client;
use mpyw\Cowitter\HttpException;

// Twitter情報ロード
include_once dirname ( __FILE__ ) . '/../settings/ArksEmergencyCall/option.php';
include_once $TwitterInfoPath;

// Twitterに接続
$client = new Client([$consumer_key, $consumer_secret,$access_token,$access_token_secret]);
$client = $client->withOptions([CURLOPT_CAINFO => __DIR__ . '/../vendor/cacert.pem']);

$json = file_get_contents(dirname ( __FILE__ ) . '/../settings/ArksEmergencyCall/userdata.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,false);

// 取得ユーザー
$screenName = "pso2_emg_hour";


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
    echo "情報更新なし";
}else{
    $arr->{$screenName}->since = $statuses[0]->id_str;
    
    $pattern = '/.*緊急クエスト予告.*01:(.*)02:(.*)03:(.*)04:(.*)05:(.*)06:(.*)07:(.*)08:(.*)09:(.*)10:(.*)#PSO2.*/isu';
    preg_match($pattern, $statuses[0]->text, $match);
    var_dump($match);
    
    // サーバーオンリー緊急判定
    if(!empty($match) && (str_replace(array("\r\n","\n","\r","　"," "), '', $match[$value->Param]) != "[発生中]")){
        if(str_replace(array("\r\n","\n","\r","　"," "), '', $match[$value->Param])  != "―"){
            $str = str_replace(array("\r\n","\n","\r"), '', "【緊急情報】Ship4 " . (date( "H" ) + 1) . ":00～ " . $match[$value->Param]);

            if($str != $arr->string){
                $discord = new DiscordWebhook($value->WebhookURL);
                $discord->name($value->UserName != "" ? $value->UserName : $statuses[0]->user->name);
                $discord->avatar($value->UserAvatar != "" ? $value->UserAvatar : $statuses[0]->user->profile_image_url);
                $discord->send($str);
                
                $arr->string = $str;
                
                echo $str;
            }else{
                echo "同じメッセージのため更新なし。<br>";
                echo $str;
            }
        }else{
            echo "緊急発生なし。";
        }
    }
    
    // 共通緊急判定
    $pattern = '/.*緊急クエスト予告.*([0-9]{2}:[0-9]{2}) *(.*) *#PSO2.*/isu';
    preg_match($pattern, $statuses[0]->text, $match);
    
    if(!empty($match)){
        $str = str_replace(array("\r\n","\n","\r"), '', "【緊急情報】共通 " . $match[1] . "～ " . $match[2]);
        
        if($str != $arr->string){
            $discord = new DiscordWebhook($value->WebhookURL);
            $discord->name($value->UserName != "" ? $value->UserName : $statuses[0]->user->name);
            $discord->avatar($value->UserAvatar != "" ? $value->UserAvatar : $statuses[0]->user->profile_image_url);
            $discord->send($str);
            
            $arr->string = $str;
            echo $str;
        }else{
            echo "同じメッセージのため更新なし。";
        }
    }
    
    ob_flush();
    flush();
    sleep(1);
}

// jsonを更新
$arr = json_encode($arr,JSON_PRETTY_PRINT);
file_put_contents(dirname ( __FILE__ ) . '/../settings/ArksEmergencyCall/userdata.json' , $arr);