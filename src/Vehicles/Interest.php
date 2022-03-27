<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Vehicles;

use Exception;
use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Interest
{
    public string $href;
    public int $count;
    public array $items;

    /**
     * Initialize.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->href = $data['href'];
        $this->count = $data['count'] ?? 0;
        $this->items = $data['items'];
    }

    /**
     * Get a contact by its ID.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param int $leadsId
     *
     * @return Interest
     */
    public static function getByLeadId(Dealer $dealer, User $user, int $leadsId) : Interest
    {
        $client = new Client($dealer->id, $user->id);

        $response = $client->get('/vehicles/interest?leadId=' . $leadsId, [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        return new Interest($response);
    }

    /**
     * Create a new Interest.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param int $leadsId
     * @param array $data
     *
     * @return self
     */
    public static function create(Dealer $dealer, User $user, int $leadsId, array $data) : self
    {
        $client = new Client($dealer->id, $user->id);

        if (!isset($data['vin'])) {
            throw new Exception('Data must contain vehicles');
        }

        $interest = [];
        $interest['lead'] = 'https://api.vinsolutions.com/leads/id/' . $leadsId;
        $interest['vehicles'][] = $data;

        $response = $client->post(
            '/vehicles/interest',
            json_encode($interest),
            [
                'headers' => [
                    'Content-Type' => 'application/vnd.coxauto.v1+json'
                ]
            ]
        );

        return self::getByLeadId($dealer, $user, $leadsId);
    }

    /**
     * Update vehicle interest.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param string $id
     * @param array $data
     *
     * @return bool
     */
    public function update(Dealer $dealer, User $user, string $id, array $data) : bool
    {
        $client = new Client($dealer->id, $user->id);

        $response = $client->put(
            '/vehicles/interest/id/' . $id,
            json_encode(
                $data
            ),
            [
                'headers' => [
                    'Content-Type' => 'application/vnd.coxauto.v1+json'
                ]
            ]
        );

        return true;
    }

    /**
     * Get specific interest.
     *
     * @param int $index
     *
     * @return array
     */
    public function getVehicleByIndex(int $index) : array
    {
        $id = str_replace('https://api.vinsolutions.com/vehicles/interest/id/', '', $this->items[$index]['href']);
        unset($this->items[$index]['href'], $this->items[$index]['lead'], $this->items[$index]['downPaymentRequested'], $this->items[$index]['monthlyPaymentRequested'], $this->items[$index]['reservationPaymentRequested'], $this->items[$index]['paymentMethod']);

        return [
            'id' => $id,
            'vehicle' => $this->items[$index]
        ];
    }
}
