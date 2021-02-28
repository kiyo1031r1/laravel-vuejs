## Laravel-Vuejs
<img src="./public/images/readme/top.png" width="800px">
<br>
<br>

## 概要
管理者が投稿した動画を、ユーザーが視聴できる動画配信サービスのWebアプリケーションです。
<br>
<br>
<a href="http://54.178.31.53/" style="font-size: 150%;">確認ページ</a>
<br>
(テストアカウントはログイン画面に表示しています)
<br>
<br>

## DEMO
ユーザー
<br>
<img src="./public/images/readme/user1.png" width="400px">
<img src="./public/images/readme/user2.png" width="400px">
<img src="./public/images/readme/user3.png" width="400px">
<img src="./public/images/readme/user4.png" width="400px">
<br>
<br>
管理者
<br>
<img src="./public/images/readme/admin1.png" width="400px">
<img src="./public/images/readme/admin2.png" width="400px">
<img src="./public/images/readme/admin3.png" width="400px">
<img src="./public/images/readme/admin4.png" width="400px">
<br>
<br>

## 機能一覧
#### 管理者
- 管理者の認証
- 動画
    - 投稿,編集,削除
    - 一覧表示,検索,ページネーション
    - 視聴,コメント表示,レコメンド表示
    - 各動画のコメント削除(全コメント可)
- ユーザー管理
    - 一覧,検索,ソート,ページネーション
    - 編集,削除
<br>

#### ユーザー
- ユーザー認証,課金ユーザー認証
- 動画
    - 一覧表示,検索,ページネーション
    - 視聴,コメント表示,レコメンド表示
    - コメント投稿,コメント返信,削除(自身のコメントのみ)
- ユーザー管理
    - 登録情報の編集
    - 課金ユーザー登録(※現在クレジット支払い機能はなく、切り替え機能のみ)
<br>
<br>

## 開発環境
- PHP - 7.3
- Laravel - 8.0
- Vue.js - 2.6.12
- Node.js - 12.18.2
- MySQL - 5.6.43
- デプロイ - AWS(EC2,S3)
<br>
<br>

- テスト
- PHPUnit - 9.5.0
- vue-test-utils - 1.1.3
- jsdom - 16.4.0
- jest - 26.6.3
- vue-jest - 3.0.7
- babel-core - 7.0.0-bridge.0
- babel-jest - 26.6.3
- babel-preset-env - 1.7.0

<br>
<br>

## ER図
<img src="./public/images/readme/ER.png" width="800px">
<br>
<br>

# 制作背景
youtubeを始め、ここ数年で動画配信サービスの人気は高まり
かなり増えてきたと思います。
<br>
その為、サービスを作れる技術は需要があるのでは？
と思った事がきっかけです。
<br>
私もyoutubeをよく利用しています。
プログラミング学習時は、ドットインストールやUdemyにお世話になりました。
<br>
今回のアプリは、これらのサービスを参考にして作成しました。
<br>
<br>
プログラミング言語やフレームワークの選択も、習得が容易で需要が高いと思われるもので作成しました。
<br>
サーバー側はPHP(Laravel)、フロント側はSPAを作成する為、Vue.jsで作成しました。
<br>
<br>

# 工夫ポイント
SPAで作成したことです。
<br>
私は、先にLaravelで従来のWebアプリケーションの作成方法を学習しました。
<br>
その為「この機能を実装したい。Laravelではこうすればできるけど、Vueでどうやるんだ？？」
<br>
という場面が多くありました。
<br>
(特に認証は苦労しました)
<br>
<br>

# 課題
- レスポンシブデザイン
- テスト(フロント部分の一部)
- 課金機能
<br>
<br>

# 今後実装したい機能
- 動画再生リスト
- 評価
<br>
<br>