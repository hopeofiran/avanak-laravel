<?php

namespace hopeofiran\avanak;

use Cassandra\Exception;
use SoapClient;

class Avanak
{
    const MALE_SPEAKER   = 'male';
    const FEMALE_SPEAKER = 'female';
    /**
     * @var object $config
     */
    public $config = [];


    /**
     * Avanak constructor.
     *
     * @param  array  $config
     *
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $this->config = empty($config) ? $this->loadDefaultConfig() : $config;
    }

    /**
     * Set custom configs
     * we can use this method when we want to use dynamic configs
     *
     * @param $key
     * @param $value  |null
     *
     * @return $this
     */
    public function config($key, $value = null)
    {
        $configs = [];
        $key     = is_array($key) ? $key : [$key => $value];
        foreach ($key as $k => $v) {
            $configs[$k] = $v;
        }
        $this->config = array_merge((array) $this->config, $configs);
        return $this;
    }

    /**
     * @param  string  $url
     *
     * @return $this
     */
    public function baseUrl(string $url)
    {
        $this->config('baseUrl', $url);
        return $this;
    }

    /**
     * Retrieve default config.
     *
     * @return array
     */
    protected function loadDefaultConfig(): array
    {
        return require(static::getDefaultConfigPath());
    }

    /**
     * Retrieve Default config's path.
     *
     * @return string
     */
    public static function getDefaultConfigPath(): string
    {
        return __DIR__ . '/config/avanak.php';
    }

    /**
     * @return \SoapClient
     * @throws \SoapFault
     */
    protected function client()
    {
        date_default_timezone_set('Asia/tehran');
        return new SoapClient($this->config['baseUrl']);
    }

    /**
     * @return string
     * @throws \SoapFault
     */
    public function getCredit()
    {
        $client = $this->client();
        $param  = [
            'userName' => $this->config['username'],
            'password' => $this->config['password'],
        ];
        try {
            return $client->GetCredit($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int     $length
     * @param  string  $number
     * @param  string  $text
     * @param  int     $serverId
     *
     * @return string
     * @throws \SoapFault
     */
    public function sendOtp(int $length, string $number, string $text, int $serverId = 0)
    {
        $client = $this->client();
        $param  = [
            'userName' => $this->config['username'],
            'password' => $this->config['password'],
            'Length'   => $length,
            'number'   => $number,
            'text'     => $text,
            'serverid' => $serverId,
        ];
        try {
            return $client->SendOTP($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  string       $title
     * @param  string       $filePath
     * @param  bool         $persist
     * @param  string|null  $callFromMobile
     *
     * @return string
     * @throws \SoapFault
     */
    public function uploadMessage(string $title, string $filePath, bool $persist = false, string $callFromMobile = null)
    {
        $client = $this->client();
        $file = file_get_contents($filePath);
        $param = [
            'userName'       => $this->config['username'],
            'password'       => $this->config['password'],
            'title'          => $title,
            'file'           => $file,
            'Persist'        => $persist,
            'CallFromMobile' => $callFromMobile,
        ];
        try {
            return $client->UploadMessage($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  string       $title
     * @param  string       $text
     * @param  string       $persist
     * @param  string|null  $callFromMobile
     *
     * @return string
     * @throws \SoapFault
     */
    public function generateTTS2(string $title, string $text, string $persist = self::MALE_SPEAKER, string $callFromMobile = null)
    {
        $client = $this->client();
        $param = [
            'userName'       => $this->config['username'],
            'password'       => $this->config['password'],
            'speaker'        => $persist,
            'text'           => $text,
            'title'          => $title,
            'CallFromMobile' => $callFromMobile,
        ];
        try {
            return $client->GenerateTTS2($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
