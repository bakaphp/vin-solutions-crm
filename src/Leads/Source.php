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
    }

    /**
     * Get all lead source.
     *
     * @param Dealer $dealer
     * @param User $user
     *
     * @return array
     */
    public static function getAll(Dealer $dealer, User $user) : array
    {
        $client = new Client($dealer->id, $user->id);
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $response = $client->get('/leadSources/?dealerId=' . $dealer->id, [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        $source = [];
        if (count($response)) {
            foreach ($response['items'] as $item) {
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
     * @param int $contactId
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
