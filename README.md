# Furima（フリマアプリ）

## アプリケーション概要
Laravelを用いたフリマアプリです。  
ユーザー登録、ログイン、商品出品、購入、コメント、いいね機能を実装しています。

---

## 環境構築

### Dockerビルド
- `git clone https://github.com/【GitHubユーザー名】/【リポジトリ名】.git`
- `cd furima`
- `docker compose up -d`

### Laravel環境構築
- `docker compose exec php sh`
- `composer install`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan migrate:fresh --seed`
- `php artisan storage:link`

---

## ダミーデータ
ユーザー：1名

商品：10件

商品画像：storage/app/public/items に保存

テスト用アカウント
email：test@example.com

password：password

開発環境URL
トップページ：http://localhost

会員登録：http://localhost/register

ログイン：http://localhost/login

Mailhog：http://localhost:8025

phpMyAdmin：http://localhost:8080

使用技術
PHP 8.x

Laravel 10.x

MySQL 8.0

Nginx

Docker / docker-compose

Mailhog

Stripe（テスト環境）