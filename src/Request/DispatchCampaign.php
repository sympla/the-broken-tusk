<?php

namespace Tracksale\Request;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tracksale\Configuration\Routes;
use Tracksale\Exception\IncorrectTypeException;
use Tracksale\Exception\InvalidRouteException;
use Tracksale\Helpers\BuildUrl;
use ReflectionException;

class DispatchCampaign
{
    const TRACKSALE_SUCESS = 200;

    /**
     * Tracksale constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $campaign_id
     * @return ResponseInterface
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    public function sendDispatchRequest(string $campaign_id, string $user_name, string $user_email): ResponseInterface
    {
        $url = $this->getDispatchUrl($campaign_id);
        $data = $this->getJsonToSend($user_name, $user_email);

        $client = new Client(['headers' => ['Authorization' => 'Bearer '.$this->token, 'Content-Type' => 'application/json']]);
        $response = $client->request('POST', $url, ['body' => $data]);
        
        if ($response->getStatusCode() != static::TRACKSALE_SUCESS) {
            throw new \Exception('Error to send campaign in Tracksale: ' . $response->getBody());
        }

        return true; 
    }

    /**
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
     * @param string $campaign_id
     * @return string
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    protected function getDispatchUrl(string $campaign_id): string
    {
        return BuildUrl::getUrlByRoute(Routes::DISPATCH) . '/' . $campaign_id . '/dispatch';
    }

    /**
     * Return instance of Guzzle
     * @return Client
     */
    protected function getGuzzleInstance(): Client
    {
        return new Client();
    }

    /**
     * Prepare array to be send to Tracksale
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
