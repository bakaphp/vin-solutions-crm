<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Leads;

use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Source
{
    public int $id;
    public string $href;
    public string $name;
    public int $itemsPerPage = 0;
    public int $total = 0;

    /**
     * Initialize.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['leadSourceId'];
        $this->href = $data['href'];
        $this->name = $data['leadSourceName'];
        $this->total = $data['count'] ?? 0;
        $this->itemsPerPage = $data['limit'] ?? 0;
    }

    /**
     * Get all lead source.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $params
     *
     * @return array
     */
    public static function getAll(Dealer $dealer, User $user, array $params = []) : array
    {
        $client = new Client($dealer->id, $user->id);
        $data = [];
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;
        $params = http_build_query($params);

        $response = $client->get('/leadSources/?dealerId=' . $dealer->id . '&' . $params, [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        $source = [];
        if (count($response)) {
            foreach ($response['items'] as $item) {
                $item['count'] = $response['count'];
                $item['itemsPerPage'] = $response['limit'];
                $source[$item['leadSourceId']] = new self($item);
            }
        }

        return $source;
    }
    /**
     * Get a contact by its ID.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param int $sourceId
     *
     * @return Source
     */
    public static function getById(Dealer $dealer, User $user, int $sourceId) : Source
    {
        $client = new Client($dealer->id, $user->id);
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $response = $client->get('/leadSources/id/' . $sourceId . '?dealerId=' . $dealer->id, [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        return new Source($response);
    }
}
