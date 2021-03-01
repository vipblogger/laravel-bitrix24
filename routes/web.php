<?php

use Vipblogger\Bitrix24DI\Bitrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('bitrix24di.')->prefix('bitrix24di')->group(static function () {

    Route::get('/', function (Bitrix $bitrix) {
        if (settings('b24_refresh_token', null) === null) {
            echo 'Авторизуйся по <a href="' . route('bitrix24di.login') . '" target="_blank">ссылке</a>
                и вставь ссылку сюда:
                <form action="' . route('bitrix24di.save') . '" method="POST">
                <input type="text" name="link">
                <button type="submit">Отправить</button>
                ' . csrf_field() . '
                </form>
                ';
        } else {
            if (!empty($bitrix->getMemberId())) {
                echo 'Связь с Битрикс: ОК';
            } else {
                echo 'Связь с Битрикс: НЕ УСТАНОВЛЕНА!';
            }

            echo ' <a href="'.route('bitrix24di.logout').'">Выйти</a>';
        }

        return '';
    })->name('index');

    Route::get('/login', function (Bitrix $bitrix) {
        if (settings('b24_refresh_token') === null) {
            return redirect('https://' . $bitrix->getDomain() . '/oauth/authorize/?redirect_uri='.url(config('bitrix24di.REDIRECT_URI')).'&client_id=' .
                urlencode($bitrix->getApplicationId()));
        }

        return '';
    })->name('login');

    Route::get('/logout', function (Bitrix $bitrix) {
        $bitrix::saveSettings([
            'refresh_token' => null,
            'access_token' => null,
            'member_id' => null,
        ]);

        return redirect()->route('bitrix24di.index');
    })->name('logout');

    Route::post('/save', function (Request $request, Bitrix $bitrix) {
        parse_str(parse_url($request->input('link'))['query'], $params);
        $code = $params['code'];

        saveCode($bitrix, $code);
        return redirect()->route('bitrix24di.index');
    })->name('save');
});

Route::get(config('bitrix24di.REDIRECT_URI'), function (Request $request, Bitrix $bitrix) {
    $code = $request->input('code');
    saveCode($bitrix, $code);
    return redirect()->route('bitrix24di.index');
});

function saveCode($bitrix, $code)
{
    $result = $bitrix->getFirstAccessToken($code);
    Bitrix::saveSettings($result);
}