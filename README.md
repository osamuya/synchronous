# synchronous


## Composer install

コンポーザーをインストールしてください。
##### Composer install
````
$ cd PATH
$ curl -s http://getcomposer.org/installer | php
````

composer.jsonを作成してインストールするパッケージを追加してください。
（通常は付属のcomposer.jsonをそのまま使ってください。）
##### make composer.json
````
$ touch composer.json

{
    "require": {
        "monolog/monolog": "1.23.0"
    }
}
````

インストール
````
$ php composer.phar install
````

## synchronous

config.phpを作成します。
````
$ cp -p config_example.php config.php
````

各種パラメータをセットして、実行します。
````
$ php synchronuos.php
````












