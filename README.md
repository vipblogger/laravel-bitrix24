Bitrix24 PHP SDK (https://github.com/mesilov/bitrix24-php-sdk) for Laravel through Dependency Injection (Service Provider, Singleton).

### 1. Installation

`composer require vipblogger/laravel-bitrix24`

### 2. Setup
Publishing the config file and migrate

`php artisan vendor:publish --provider="Vipblogger\LaravelBitrix24\LaravelBitrix24ServiceProvider"`

`php artisan migrate`

### 3. In Bitrix24
Register local application with redirect URI `/bitrix-redirect-uri`. Redirect URI is optional.

### 4. Edit .env
.env example

```
B24_APPLICATION_ID=local.6007b39598c****.120****
B24_APPLICATION_SECRET=oq33VjMcuperGAnB...
B24_DOMAIN=example.bitrix24.ru
B24_APPLICATION_SCOPE=task,user
B24_REDIRECT_URI=/bitrix-redirect-uri
```

### 5. Bitrix24 settings
Go to route *your_project.com/**laravel-bitrix24***

### 6. Usage

```php
use Vipblogger\LaravelBitrix24\Bitrix;

Route::get('/test1', function (Bitrix $bitrix) {
    $result = $bitrix->call('lists.get', [
        'IBLOCK_TYPE_ID' => 'lists_socnet',
        'SOCNET_GROUP_ID' => 15
        ]);

    var_dump($result);
});

Route::get('/test2', function (Bitrix $bitrix) {
    $obB24User = new \Bitrix24\User\User($bitrix);
    $arCurrentB24User = $obB24User->current();
    var_dump($arCurrentB24User);
});
```

More documentation: https://github.com/mesilov/bitrix24-php-sdk