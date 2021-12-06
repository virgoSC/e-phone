<?php

namespace EPhone;

use EPhone\Http\Request;
use EPhone\Http\Response\LoginResponse;
use EPhone\Http\Response\NotifyResponse;
use EPhone\Http\Response\PhoneResponse;
use EPhone\Http\Response\Response;
use EPhone\Http\Response\SeatResponse;
use EPhone\Http\Response\SeatStatusResponse;

/**
 * //42_e0656
 * @method LoginResponse login()
 * @method PhoneResponse phone($activePhone, $passivePhone, $seats, $notifyUrl = '')
 * @method SeatResponse createSeat($activePhone, $name)
 * @method Response updateSeat($seatId, $activePhone)
 * @method Response deleteSeat($seatId, $activePhone)
 * @method SeatStatusResponse seatStatus($seatId)
 * @method Response captchaResend($activePhone)
 * @method Response whiteAppend($phone, $idCard, $name)
 * @method NotifyResponse notify($data)
 */
class EPhone
{
    public $token;

    public $url;

    public $appid;

    public $uname;

    public $password;

    public $notifyUrl;

    public $sig;
    /**
     * @var Request $request
     */
    private $request;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->url = $config['url'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->uname = $config['uname'] ?? '';
        $this->appid = $config['appid'] ?? '';
        $this->notifyUrl = $config['notifyUrl'] ?? '';
        $this->sig = $config['sig'] ?? '';

        $this->request = new Request($this);
    }

    public function bindToken(string $token)
    {
        $this->token = $token;
    }

    public function isCurl(bool $isCurl = true)
    {
        $this->request->isCurl = $isCurl;
    }

    public function proxy($proxyHost, $proxyPort)
    {
        $this->request->setProxy($proxyHost, $proxyPort);
    }

    public function __call($name, $arguments): Response
    {
        return $this->request->acquire($name, $arguments);
    }
}