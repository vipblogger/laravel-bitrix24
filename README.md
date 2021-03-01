### 1. Installation

`composer require vipblogger/bitrix24-di`

### 2. Setup
Publishing the config file and migrate

`php artisan vendor:publish --provider="Vipblogger\Bitrix24DI\Bitrix24DIServiceProvider"`

`php artisan migrate`

### 3. In Bitrix24
Register local application with redirect URI `/bitrix-redirect-uri`. Redirect URI is optional.

### 4. Edit .env
.env example

```
B24_APPLICATION_SCOPE=task,user
B24_APPLICATION_ID=local.6007b39598c****.120****
B24_APPLICATION_SECRET=oq33VjMcuperGAnB...
B24_DOMAIN=example.bitrix24.ru
B24_REDIRECT_URI=/bitrix-redirect-uri
```

### 5. Bitrix24 settings
Go to route *your_project.com/**bitrix24di***

### 6. Usage

```php
use Vipblogger\Bitrix24DI\Bitrix;

Route::get('/list', function (Bitrix $bitrix) {
    $result = $bitrix->call('lists.get', [
        'IBLOCK_TYPE_ID' => 'lists_socnet',
        'SOCNET_GROUP_ID' => 15
        ]);

    var_dump($result);
});
```