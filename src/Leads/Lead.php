<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Leads;

use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Lead
{
    public int $id;
    public ?string $leadSource = null;
    public ?string $leadStatusType = null;
    public int $leadStatusId = 0;
    public int $leadType = 0;
    public int $contactId = 0;
    public int $isHot = 0;
    public int $isOnShowroom = 0;


    /**
     * Initialize.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['LeadId'] ?? $data['leadId'];
        $this->leadSource = $data['LeadSource'] ?? null;
        $this->leadStatusType = $data['LeadStatusType'] ?? null;
        $this->leadStatusId = $data['LeadStatusId'] ?? 0;
        $this->leadType = $data['LeadType'] ?? 0;
        $this->contactId = $data['CustomerId'] ?? 0;
        $this->isHot = isset($data['IsHot']) ? (int) $data['IsHot'] : 0;
        $this->isOnShowroom = isset($data['IsOnShowroom']) ? (int) $data['IsOnShowroom'] : 0;
    }

    /**
     * Get a contact by its ID.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param int $contactId
     *
     * @return self
     */
    public static function getById(Dealer $dealer, User $user, int $leadsId) : self
    {
        $client = new Client($dealer->id, $user->id);
        $client->useDigitalShowRoomKey();

        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $response = $client->get(
            '/gateway/v1/lead/' . $leadsId . '?dealerId=' . $dealer->id . '&userId=' . $user->id,
        );

        return new self($response['Leads'][0]);
    }

    /**
     * Create a new contact.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $data
     *
     * @return self
     */
    public static function create(Dealer $dealer, User $user, array $data) : self
    {
        $client = new Client($dealer->id, $user->id);
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $data['leadSource'] = 'https://api.vinsolutions.com/leadsources/id/' . $data['leadSource'] . '?dealerId=' . $dealer->id;
        $data['contact'] = 'https://api.vinsolutions.com/leadsources/id/' . $data['contact'] . '?dealerId=' . $dealer->id;

        $response = $client->post(
            '/leads?dealerId=' . $dealer->id . '&userId=' . $user->id,
            json_encode($data),
            [
                'headers' => [
                    'Content-Type' => 'application/vnd.coxauto.v3+json'
                ]
            ]
        );

        return new self($response);
    }

    /**
     * Create a new contact.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $data
     *
     * @return self
     */
    public function update(Dealer $dealer, User $user) : self
    {
        $client = new Client($dealer->id, $user->id);

        $data = [];
        $data['isHot'] = $this->isHot ? true : false;

        $response = $client->put(
            '/leads/id/' . $this->id,
            json_encode($data),
            [
                'headers' => [
                    'Content-Type' => 'application/vnd.coxauto.v3+json'
                ]
            ]
        );

        $data['LeadId'] = $this->id;
        return new self($data);
    }
}
