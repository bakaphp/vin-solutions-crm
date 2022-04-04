<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Vehicles;

use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class TradeIn
{
    public string $href;
    public int $count;
    public array $items;

    /**
     * Initialize
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
     * @return TradeIn
     */
    public static function getByLeadId(Dealer $dealer, User $user, int $leadsId) : TradeIn
    {
        $client = new Client($dealer->id, $user->id);

        $response = $client->get('/vehicles/trade?leadId=' . $leadsId, [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        return new TradeIn($response);
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
    public static function update(Dealer $dealer, User $user, string $id, array $data) : bool
    {
        $client = new Client($dealer->id, $user->id);

        $response = $client->put(
            '/vehicles/trade/id/' . $id,
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
        $id = str_replace('https://api.vinsolutions.com/vehicles/trade/id/', '', $this->items[$index]['href']);
        unset($this->items[$index]['href'], $this->items[$index]['lead'], $this->items[$index]['downPaymentRequested'], $this->items[$index]['monthlyPaymentRequested'], $this->items[$index]['reservationPaymentRequested'], $this->items[$index]['paymentMethod']);

        return [
            'id' => $id,
            'vehicle' => $this->items[$index]
        ];
    }
}
