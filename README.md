# NewTwitterGeter
Discordに簡単に投稿することを目標にした物。

## 現在ある機能
- NewUserTweet  
特定ユーザーのツイートを取得し投稿する。
- ArksEmergencyCall  
SEGAが運営するPSO2の緊急クエストを通知する。
- Test  
パラメーターをそのまま投稿するだけ

## 必要なもの
**php/cronの動作する環境**  
Twitter Application Managementから取得できるコンシューマーキーなど  

## 使い方
- users.jsonに指定の処理を書き加えます。  
        {  
            "Function": "上記の機能名",  
            "Param": "パラメーター include",  
            "WebhookURL":   "https://discordapp.com/api/webhooks/..... WebhookのURL",  
            "UserName": "Discord内で表示されるユーザー名",  
            "UserAvatar": "DIscord内で表示される画像(jpg/png推奨)"  
        }  
- cron.phpを叩きます。

## ライセンス
### DiscordHooks
MITLicense  

### cowitter
MIT License / https://github.com/mpyw/cowitter

Copyright (c) 2016 mpyw  
Permission is hereby granted, free of charge, to any person obtaining a copy  
of this software and associated documentation files (the "Software"), to deal  
in the Software without restriction, including without limitation the rights  
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell  
copies of the Software, and to permit persons to whom the Software is  
furnished to do so, subject to the following conditions:  

The above copyright notice and this permission notice shall be included in all  
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,  
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE  
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER  
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE  
SOFTWARE.  
