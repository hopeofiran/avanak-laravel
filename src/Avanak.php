<?php

namespace hopeofiran\avanak;

use Cassandra\Exception;
use Illuminate\Support\Carbon;
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

    /**
     * @param  string                      $title
     * @param                              $numbers
     * @param  int                         $maxTryCount
     * @param  int                         $minuteBetweenTries
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @param  int                         $messageId
     * @param  bool                        $removeInvalids
     * @param  int                         $serverId
     * @param  bool                        $autoStart
     * @param  bool                        $vote
     *
     * @return string
     * @throws \SoapFault
     */
    public function createCampaign(string $title, $numbers, int $maxTryCount, int $minuteBetweenTries, Carbon $start, Carbon $end, int $messageId, bool $removeInvalids = false, int $serverId = 0, bool $autoStart = false, bool $vote = false)
    {
        $numbers = is_array($numbers) ? implode(',', $numbers) : $numbers;
        $client = $this->client();
        $param = [
            'userName'           => $this->config['username'],
            'password'           => $this->config['password'],
            'title'              => $title,
            'numbers'            => $numbers,
            'maxTryCount'        => $maxTryCount,
            'minuteBetweenTries' => $minuteBetweenTries,
            'startDate'          => $start->format('Y-m-d'),
            'endDate'            => $end->format('Y-m-d'),
            'startTime'          => $start->format('H:i:s'),
            'endTime'            => $end->format('H:i:s'),
            'messageId'          => $messageId,
            'removeInvalids'     => $removeInvalids,
            'serverId'           => $serverId,
            'autoStart'          => $autoStart,
            'vote'               => $vote,
        ];
        try {
            return $client->CreateCampaign($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  string                      $title
     * @param                              $numbers
     * @param  string                      $GISCroods
     * @param  int                         $maxTryCount
     * @param  int                         $minuteBetweenTries
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @param  int                         $messageId
     * @param  bool                        $removeInvalids
     * @param  int                         $serverId
     * @param  bool                        $autoStart
     * @param  bool                        $vote
     *
     * @return string
     * @throws \SoapFault
     */
    public function createCampaignGIS(string $title, $numbers, string $GISCroods, int $maxTryCount, int $minuteBetweenTries, Carbon $start, Carbon $end, int $messageId, bool $removeInvalids = false, int $serverId = 0, bool $autoStart = false, bool $vote = false)
    {
        $numbers = is_array($numbers) ? implode(',', $numbers) : $numbers;
        $client = $this->client();
        $param = [
            'userName'           => $this->config['username'],
            'password'           => $this->config['password'],
            'title'              => $title,
            'numbers'            => $numbers,
            'maxTryCount'        => $maxTryCount,
            'GISCroods'          => $GISCroods,
            'minuteBetweenTries' => $minuteBetweenTries,
            'startDate'          => $start->format('Y-m-d'),
            'endDate'            => $end->format('Y-m-d'),
            'startTime'          => $start->format('H:i:s'),
            'endTime'            => $end->format('H:i:s'),
            'messageId'          => $messageId,
            'removeInvalids'     => $removeInvalids,
            'serverId'           => $serverId,
            'autoStart'          => $autoStart,
            'vote'               => $vote,
        ];
        try {
            return $client->CreateCampaignGIS($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int  $campaignId
     *
     * @return string
     * @throws \SoapFault
     */
    public function stopCampaign(int $campaignId)
    {
        $client = $this->client();
        $param = [
            'userName'   => $this->config['username'],
            'password'   => $this->config['password'],
            'campaignId' => $campaignId,
        ];
        try {
            return $client->StopCampaign($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int  $campaignId
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignById(int $campaignId)
    {
        $client = $this->client();
        $param = [
            'userName'   => $this->config['username'],
            'password'   => $this->config['password'],
            'campaignId' => $campaignId,
        ];
        try {
            return $client->GetCampaignById($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  \Illuminate\Support\Carbon  $from
     * @param  \Illuminate\Support\Carbon  $to
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignsByDate(Carbon $from, Carbon $to)
    {
        $client = $this->client();
        $param = [
            'userName'   => $this->config['username'],
            'password'   => $this->config['password'],
            'fromDate' => $from->format('Y-m-d'),
            'toDate' => $to->format('Y-m-d'),
        ];
        try {
            return $client->GetCampaignsByDate($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int  $campaignId
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignNumbersByCampaignId(int $campaignId)
    {
        $client = $this->client();
        $param = [
            'userName'   => $this->config['username'],
            'password'   => $this->config['password'],
            'campaignId' => $campaignId,
        ];
        try {
            return $client->GetCampaignNumbersByCampaignId($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int  $messageId
     * @param  int  $lastId
     * @param  int  $count
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignNumbersByMessageId(int $messageId, int $lastId = 0, int $count = 0)
    {
        $client = $this->client();
        $param = [
            'userName'  => $this->config['username'],
            'password'  => $this->config['password'],
            'messageId' => $messageId,
            'lastid'    => $lastId,
            'count'     => $count,
        ];
        try {
            return $client->GetCampaignNumbersByMessageId($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  \Illuminate\Support\Carbon  $from
     * @param  \Illuminate\Support\Carbon  $to
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignNumbersBySendDate(Carbon $from, Carbon $to)
    {
        $client = $this->client();
        $param = [
            'userName' => $this->config['username'],
            'password' => $this->config['password'],
            'fromDate' => $from->format('Y-m-d'),
            'toDate'   => $to->format('Y-m-d'),
        ];
        try {
            return $client->GetCampaignNumbersBySendDate($param);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param  int  $subscribeId
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignNumbersBySubscribeId(int $subscribeId)
    {
        $client = $this->client();
        $param = [
            'userName' => $this->config['username'],
            'password' => $this->config['password'],
            'subscribeId' => $subscribeId,
        ];
        try {
            return $client->GetCampaignNumbersBySubscribeId($param);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param  array  $campaignNumberIds
     *
     * @return string
     * @throws \SoapFault
     */
    public function getCampaignNumbersDataByIds(array $campaignNumberIds)
    {
        $campaignNumberIds = implode(',', $campaignNumberIds);
        $client = $this->client();
        $param = [
            'userName'          => $this->config['username'],
            'password'          => $this->config['password'],
            'campaignNumberIds' => $campaignNumberIds,
        ];
        try {
            return $client->GetCampaignNumbersDataByIds($param);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param  array  $campaignNumberIds
     *
     * @return \Cassandra\Exception|\Exception
     * @throws \SoapFault
     */
    public function getCampaignNumbersStatusByIds(array $campaignNumberIds)
    {
        $campaignNumberIds = implode(',', $campaignNumberIds);
        $client = $this->client();
        $param = [
            'userName'          => $this->config['username'],
            'password'          => $this->config['password'],
            'campaignNumberIds' => $campaignNumberIds,
        ];
        try {
            return $client->GetCampaignNumbersStatusByIds($param);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * @param  string  $number
     * @param  int     $messageId
     * @param  int     $serverId
     * @param  bool    $vote
     *
     * @return string
     * @throws \SoapFault
     */
    public function quickSend(string $number, int $messageId, int $serverId = 0, bool $vote = false)
    {
        $client = $this->client();
        $param = [
            'userName'  => $this->config['username'],
            'password'  => $this->config['password'],
            'number'    => $number,
            'messageId' => $messageId,
            'serverid'  => $serverId,
            'vote'      => $vote,
        ];
        try {
            return $client->QuickSend($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  int  $quickSendId
     *
     * @return string
     * @throws \SoapFault
     */
    public function getQuickSend(int $quickSendId)
    {
        $client = $this->client();
        $param = [
            'userName'    => $this->config['username'],
            'password'    => $this->config['password'],
            'quickSendId' => $quickSendId,
            'price'       => true,
        ];
        try {
            return $client->GetQuickSend($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param  string       $number
     * @param  string       $text
     * @param  int          $serverId
     * @param  bool         $vote
     * @param  string|null  $callFromMobile
     *
     * @return string
     * @throws \SoapFault
     */
    public function quickSendWithTTS(string $number, string $text, int $serverId = 0, bool $vote = false, string $callFromMobile = null)
    {
        $client = $this->client();
        $param = [
            'userName'       => $this->config['username'],
            'password'       => $this->config['password'],
            'number'         => $number,
            'text'           => $text,
            'serverid'       => $serverId,
            'vote'           => $vote,
            'callFromMobile' => $callFromMobile,
        ];
        try {
            return $client->QuickSendWithTTS($param);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
