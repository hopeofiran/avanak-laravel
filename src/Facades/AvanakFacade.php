<?php

namespace hopeofiran\avanak\Facades;

use hopeofiran\avanak\Avanak;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 *
 * @package Shetabit\Payment\Facade
 *
 * @method static Avanak baseUrl(string $url)
 * @method static Avanak config(string|array $keys, string $value = null)
 * @method static getCredit(): string
 * @method static sendOtp(int $length, string $number, string $text, int $serverId = 0): string
 * @method static uploadMessage(string $title, string $filePath, bool $persist = false, string $callFromMobile = null): string
 * @method static generateTTS2(string $title, string $text, string $persist = Avanak::MALE_SPEAKER, string $callFromMobile = null): string
 * @method static createCampaign(string $title, array $numbers, int $maxTryCount, int $minuteBetweenTries, Carbon $start, Carbon $end, int $messageId, bool $removeInvalids = false, int $serverId = 0, bool $autoStart = false, bool $vote = false): string
 * @method static createCampaignGIS(string $title, $numbers, string $GISCroods, int $maxTryCount, int $minuteBetweenTries, Carbon $start, Carbon $end, int $messageId, bool $removeInvalids = false, int $serverId = 0, bool $autoStart = false, bool $vote = false): string
 * @method static stopCampaign(int $campaignId): string
 * @method static getCampaignById(int $campaignId): string
 * @method static getCampaignsByDate(Carbon $from, Carbon $to): string
 * @method static getCampaignNumbersByCampaignId(int $campaignId): string
 * @method static getCampaignNumbersByMessageId(int $messageId, int $lastId = 0, $count = 0): string
 * @method static getCampaignNumbersBySendDate(Carbon $from, Carbon $to): string
 * @method static getCampaignNumbersBySubscribeId(int $campaignId): string
 * @method static getCampaignNumbersDataByIds(array $campaignNumberIds): string
 * @method static getCampaignNumbersStatusByIds(array $campaignNumberIds): string
 * @method static quickSend(string $number, int $messageId, int $serverId = 0, bool $vote = false): string
 * @method static getQuickSend(string $quickSendId): string
 * @method static quickSendWithTTS(string $number, string $text, int $serverId = 0, bool $vote = false, string $callFromMobile = null): string
 * @method static getMessages(): string
 * @method static getMessage(int $messageId): string
 * @method static deleteMessage(int $messageId): string
 * @method static downloadMessage(int $messageId): string
 *
 */
class AvanakFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'avanak';
    }
}
