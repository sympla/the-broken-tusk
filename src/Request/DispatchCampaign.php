<?php

namespace Tracksale\Request;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tracksale\Configuration\Http;
use Tracksale\Exception\IncorrectTypeException;
use Tracksale\Exception\InvalidRouteException;
use Tracksale\Exception\RequestFailed;
use Tracksale\Helpers\BuildUrl;

class DispatchCampaign
{
    /** @var int */
    const TRACKSALE_SUCESS = 200;

    /** @var string  */
    protected $token;

    /**
     * Tracksale constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Prepare to send request, formating data and send to DispatchRequest from tracksale
     * 
     * @param string $campaign_id
     * @param string $user_name
     * @param string $user_email
     * @return ResponseInterface
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    public function sendDispatchRequest(string $campaign_id, string $user_name, string $user_email): bool
    {
        $url = $this->getDispatchUrl($campaign_id);
        $data = $this->getJsonToSend($user_name, $user_email);

        $client = $this->getGuzzleInstance();
        $response = $client->request('POST', $url, ['body' => $data]);

        if ($response->getStatusCode() != self::TRACKSALE_SUCESS) {
            throw new RequestFailed('Error to send campaign in Tracksale', $response->getStatusCode(), json_decode($response->getBody()));
        }

        return true; 
    }

    /**
     * Verify if email is valid before send 
     * 
     * @param string $email
     * @throws IncorrectTypeException
     */
    protected function emailValidate(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectTypeException("Not a valid email: " . $email);
        }
    }

    /**
     * Stance of BuildUrl
     * 
     * @return BuildUrl
     */
    protected function getBuildUrl()
    {
        return new BuildUrl();
    }

    /**
     * Generate complete url to send in request
     * 
     * @param string $campaign_id
     * @return string
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    protected function getDispatchUrl(string $campaign_id): string
    {
        return self::getBuildUrl()->getUrlByRoute(Http::ROUTES['DISPATCH']) . '/' . $campaign_id . '/dispatch';
    }
    
    /**
     * Return instance of Guzzle
     * 
     * @return Client
     */
    protected function getGuzzleInstance(): Client
    {
        return new Client(['headers' => ['Authorization' => 'Bearer '.$this->token, 'Content-Type' => 'application/json']]);
    }

    /**
     * Prepare array to be send to Tracksale
     * 
     * @param string $user_name
     * @param string $user_email
     * @return string
     */
    protected function getJsonToSend(string $user_name, string $user_email): string
    {
        $this->emailValidate($user_email);

        $delay = 60;
        $dateToSend = date("U", strtotime("+$delay sec"));

        $data['customers'] = [ 
                                [   
                                    'name' =>  $user_name,
                                    'email' => $user_email
                                ]
                            ];

        $data['schedule_time'] = $dateToSend;

        return json_encode($data);
    }
}
