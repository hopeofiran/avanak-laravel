<?php

namespace hopeofiran\avanak\Facades;

use hopeofiran\avanak\Avanak;
use Illuminate\Support\Facades\Facade;

/**
 * Class Payment
 *
 * @package Shetabit\Payment\Facade
 *
 * @method static Avanak backUrl($url)
 * @method static Avanak config($key, $value = null)
 * @method static getCredit(): string
 * @method static sendOtp(int $length, string $number, string $text, int $serverId = 0): string
 * @method static uploadMessage(string $title, string $file, bool $persist = false, string $callFromMobile = null): string
 * @method static generateTTS2(string $title, string $text, string $persist = Avanak::MALE_SPEAKER, string $callFromMobile = null): string
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
