<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions;

use function Baka\envValue;

use GuzzleHttp\Client as GuzzleClient;
use Phalcon\Di;
use Redis;

/**
 * Wrapper for the VinSolutions API.
 */
class Client
{
    protected GuzzleClient $client;
    protected int $dealerId;
    protected int $userId;
    protected string $authBaseUrl = 'https://authentication.vinsolutions.com';
    protected string $baseUrl = 'https://api.vinsolutions.com';
    protected string $grantType = 'client_credentials';
    protected string $scope = 'PublicAPI';
    protected string $clientId;
    protected string $clientSecret;
    protected string $apiKey;
    protected Redis $redis;
    protected string $redisKey = 'vinSolutionAuthToken';

    /**
     * Constructor.
     *
     * @param int $dealerId
     * @param int $userId
     */
    public function __construct(int $dealerId, int $userId)
    {
        $this->dealerId = $dealerId;
        $this->userId = $userId;

        $this->clientId = envValue('VINSOLUTIONS_CLIENT_ID');
        $this->clientSecret = envValue('VINSOLUTIONS_CLIENT_SECRET');
        $this->apiKey = envValue('VINSOLUTIONS_API_KEY');

        if (!Di::getDefault()->has('redis')) {
            $this->redis = new Redis();
            $this->redis->connect(envValue('REDIS_HOST', '127.0.0.1'));
        } else {
            $this->redis = Di::getDefault()->get('redis');
        }

        $this->client = new GuzzleClient(
            [
                'base_uri' => $this->baseUrl,
                'timeout' => 2.0
            ]
        );
    }

    /**
     * Authenticate with the VinSolutions API.
     *
     * @return array
     */
    public function auth() : array
    {
        if (!$token = $this->redis->get($this->redisKey)) {
            $response = $this->client->post(
                $this->authBaseUrl . '/connect/token',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'form_params' => [
                        'grant_type' => $this->grantType,
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'scope' => $this->scope
                    ]
                ]
            );

            $token = $response->getBody()->getContents();


            //set the token in redis
            $this->redis->set(
                $this->redisKey,
                $token,
                3300
            );
        }

        return json_decode($token, true);
    }

    /**
     * Set this request headers.
     *
     * @param array $headers
     *
     * @return array
     */
    protected function setHeaders(array $headers) : array
    {
        $headers['headers'] = [
            'api_key' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->auth()['access_token'],
        ];
        return $headers;
    }

    /**
     * Run Get request against VinSolutions API.
     *
     * @param string $path
     * @param array $params
     *
     * @return array
     */
    public function get(string $path, array $params = []) : array
    {
        $response = $this->client->get(
            $path,
            $this->setHeaders($params)
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    /**
     * Post to the api.
     *
     * @param string $path
     * @param string $params
     *
     * @return array
     */
    public function post(string $path, string $json) : array
    {
        $params = $this->setHeaders([]);
        $params['headers']['Content-Type'] = 'application/json';
        $params['body'] = $json;

        $response = $this->client->post(
            $path,
            $params
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    /**
     * Post to the api.
     *
     * @param string $path
     * @param string $params
     *
     * @return array
     */
    public function put(string $path, string $json) : array
    {
        $params = $this->setHeaders([]);
        $params['headers']['Content-Type'] = 'application/json';
        $params['body'] = $json;

        $response = $this->client->put(
            $path,
            $params
        );

        return !empty($response->getBody()->getContents()) ? json_decode(
            $response->getBody()->getContents(),
            true
        ) : [];
    }
}