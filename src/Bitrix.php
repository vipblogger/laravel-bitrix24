<?php


namespace Vipblogger\Bitrix24DI;


use Bitrix24\Bitrix24;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

/**
 * Class VipBitrix24
 * @package App\Services
 */
class Bitrix extends Bitrix24
{

    public function __construct($isSaveRawResponse = false, LoggerInterface $obLogger = null)
    {
        parent::__construct($isSaveRawResponse, $obLogger);
    }

    public static function saveSettings($result)
    {
        // save new settings
        try {
            settings()->set('b24_member_id', $result['member_id']);
            settings()->set('b24_access_token', $result['access_token']);
            settings()->set('b24_refresh_token', $result['refresh_token']);
        } catch (QueryException $e) {
            Log::error('QueryException: DI/Bitrix saveSettings()');
        }
    }

    public function setLogger($obLogger)
    {
        $this->log = clone $obLogger;
    }
}