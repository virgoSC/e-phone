<?php

namespace EPhone\Http;

use EPhone\EPhone;
use EPhone\Http\Response\NotifyResponse;
use EPhone\Http\Response\PhoneResponse;
use EPhone\Http\Response\SeatResponse;
use EPhone\Http\Response\SeatStatusResponse;
use GuzzleHttp\Client;
use EPhone\Http\Response\Response;
use EPhone\Http\Response\LoginResponse;
use GuzzleHttp\Exception\GuzzleException;

class Request
{

    public $isCurl = true;

    public $timeout = 5;

    private $proxyHost;

    private $proxyPort;

    private $ePhone;

    private $path;

    private $urlSet = [
        'login' => '/vo/login/login',
        'phone' => '/vo/api/entrance/bind',
        'createSeat' => '/vo/seat-phone/add',
        'updateSeat' => '/vo/seat-phone/update',
        'deleteSeat' => '/vo/seat-phone/del',
        'seatStatus' => '/vo/seat-phone/get_status',
        'captchaResend' => '/vo/seat-phone/h_get_code',
        'whiteAppend' => '/vo/call/add-white-yd',
    ];

    public function __construct(EPhone $ePhone)
    {
        $this->ePhone = $ePhone;
    }

    public function acquire($path, $arguments): Response
    {
        $this->path = $path;

        switch ($this->path) {
            case 'login':
                return $this->request([
                    'username' => $this->ePhone->uname,
                    'password' => $this->ePhone->password
                ], LoginResponse::class, false);
            case 'phone':
                return $this->request([
                    'activePhone' => $arguments[0],
                    'passivePhone' => $arguments[1],
                    'ip' => $arguments[2],
                    'appid' => $this->ePhone->appid,
                    'url' => $arguments[3] ?? $this->ePhone->notifyUrl
                ], PhoneResponse::class);
            case 'createSeat':
                return $this->request([
                    'activePhone' => $arguments[0],
                    'name' => $arguments[1],
                    'appid' => $this->ePhone->appid,
                    'sig' => $this->ePhone->sig,
                ], SeatResponse::class);
            case "deleteSeat":
            case "updateSeat" :
                return $this->request([
                    'id' => $arguments[0],
                    'activePhone' => $arguments[1],
                    'appid' => $this->ePhone->appid,
                ]);
            case 'seatStatus':
                return $this->request([
                    'id' => $arguments[0],
                    'appid' => $this->ePhone->appid,
                ], SeatStatusResponse::class);
            case 'captchaResend':
                return $this->request([
                    'activePhone' => $arguments[0],
                    'appid' => $this->ePhone->appid,
                ]);
            case 'whiteAppend':
                return $this->request([
                    'phone' => $arguments[0],
                    'idCard' => $arguments[1],
                    'name' => $arguments[2],
                    'appid' => $this->ePhone->appid,
                ]);
            case 'notify':
                return (new NotifyResponse())->building($arguments[0]);

            default :
                return (new Response())
                    ->setCode('500')
                    ->setError('?????????????????????');
        }
    }

    public function setProxy($host, $port)
    {
        $this->proxyHost = $host;
        $this->proxyPort = $port;
    }

    private function request($param, $Response = Response::class, $hasToken = true): Response
    {
        $this->path = $this->urlSet[$this->path];
        $response = new $Response;

//        $response->setCode(200)
//            ->setBody('{"statusCode":"50006","message":"????????????","data":null}')
//            ->setHeader([])
//            ->resolve();
//        return $response;

        try {
            if ($this->isCurl) {
                $ch = curl_init();//?????????curl
                curl_setopt($ch, CURLOPT_URL, $this->ePhone->url . $this->path);
                curl_setopt($ch, CURLOPT_HEADER, 0);//??????header
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");//post????????????
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout); //??????
//                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 10); //??????
                curl_setopt($ch, CURLOPT_ENCODING, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'X-Access-Token: ' . ($hasToken ? $this->ePhone->token : '')
                ));

                //??????
                if ($this->proxyPort and $this->proxyHost) {
                    curl_setopt($ch, CURLOPT_PROXY, $this->proxyHost);
                    curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxyPort);
                }

                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param, JSON_UNESCAPED_UNICODE));
                $data = curl_exec($ch);//??????curl

                $resStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if (!$data) {
                    throw new \Exception(curl_error($ch));
                }
                curl_close($ch);

                $response->setCode($resStatusCode)
                    ->setBody($data)
                    ->setHeader([])
                    ->resolve();

            } else {
                $client = new Client();
                $res = $client->request('POST', $this->ePhone->url . $this->path, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'X-Access-Token' => $hasToken ? $this->ePhone->token : '',
                    ],
                    'json' => $param,
                    'debug' => false
                ]);
                $resBody = $res->getBody()->getContents();

                $response->setCode($res->getStatusCode())
                    ->setBody($resBody)
                    ->setHeader($res->getHeaders())
                    ->resolve();

            }
            return $response;

        } catch (\Exception $e) {
            if (isset($ch)) {
                curl_close($ch);
            }
            $response->setCode('500')
                ->setError($e->getMessage(), $this->timeout);
            return $response;
        } catch (GuzzleException $e) {
            $response->setCode('400')
                ->setError($e->getMessage(), $this->timeout);
            return $response;
        }
    }

}
