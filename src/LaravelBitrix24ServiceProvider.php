<?php

namespace Vipblogger\LaravelBitrix24;

use Bitrix24\Exceptions\Bitrix24TokenIsInvalidException;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LaravelBitrix24ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Bitrix::class, function ($app) {
            return new Bitrix;
        });
    }

    public function boot(Bitrix $obB24App)
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-bitrix24.php' => config_path('laravel-bitrix24.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Bitrix settings
        // create a log channel
        $log = new Logger('laravel-bitrix24');
        $log->pushHandler(new StreamHandler(storage_path() . '/logs/laravel-bitrix24.log', Logger::DEBUG));

        $arParams = config('laravel-bitrix24');

        if (!$arParams) {
            return false;
        }

        $obB24App->setApplicationScope($arParams['B24_APPLICATION_SCOPE']);
        $obB24App->setApplicationId($arParams['B24_APPLICATION_ID']);
        $obB24App->setApplicationSecret($arParams['B24_APPLICATION_SECRET']);
        $obB24App->setDomain($arParams['DOMAIN']);
        $obB24App->setRedirectUri(url($arParams['REDIRECT_URI']));

        try {
            $refreshToken = settings('b24_refresh_token');

            if ($refreshToken) {
                // access key expired
                if (time() >= (settings('b24_expires', 0) - 300)) {
                    $obB24App->setRefreshToken($refreshToken);
                    $result = $obB24App->getNewAccessToken();
                    $obB24App->setAccessToken($result['access_token']);

                    // save new settings
                    Bitrix::saveSettings($result);
                }

                // from DB
                $obB24App->setMemberId(settings('b24_member_id'));
                $obB24App->setAccessToken(settings('b24_access_token'));
            }
        } catch (QueryException $e) {
            echo 'QueryException settings table';
        } catch (Bitrix24TokenIsInvalidException $e) {
            echo 'Bitrix24TokenIsInvalidException!';
        }
    }

    public function provides()
    {
        return [Bitrix::class];
    }
}