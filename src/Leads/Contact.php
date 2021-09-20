<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Leads;

use Illuminate\Support\Str;
use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Contact
{
    public int $id;
    public array $information = [];
    public array $emails = [];
    public array $phones = [];
    public array $customerConsent = [];
    public array $dealerTeam = [];
    public array $smsPreferences = [];
    public array $leadInformation = [];

    /**
     * Initialize.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['ContactId'];
        $this->information = $data['ContactInformation'] ?? [];
        $this->customerConsent = $data['CustomerConsent'] ?? [];
        $this->emails = $this->information['Emails'] ?? [];
        $this->phones = $this->information['Phones'] ?? [];
        $this->smsPreferences = $data['SmsPreferences'] ?? [];
        $this->dealerTeam = $data['DealerTeam'] ?? [];
        $this->leadInformation = $data['leadInformation'] ?? [];
    }

    /**
     * Get a contact by its ID.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param int $contactId
     *
     * @return Contact
     */
    public static function getById(Dealer $dealer, User $user, int $contactId) : Contact
    {
        $client = new Client($dealer->id, $user->id);
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $response = $client->get('/gateway/v1/contact/' . $contactId . '?dealerId=' . $dealer->id . '&userId=' . $user->id);

        return new Contact($response[0]);
    }

    /**
     * Create a new contact.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $data
     *
     * @return Contact
     */
    public static function create(Dealer $dealer, User $user, array $data) : self
    {
        $client = new Client($dealer->id, $user->id);
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        if (isset($data['ContactInformation']['Phones'])) {
            $data['ContactInformation']['Phones'][0]['Number'] = Str::limit($data['ContactInformation']['Phones'][0]['Number'], 10, '');
        }

        $response = $client->post('/gateway/v1/contact', json_encode($data));

        return new self($response);
    }

    /**
     * Create a new contact.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $data
     *
     * @return Contact
     */
    public function update(Dealer $dealer, User $user) : self
    {
        $client = new Client($dealer->id, $user->id);

        $data = [];
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $this->information['Emails'] = $this->emails;
        $this->information['Phones'] = $this->phones;

        $data['ContactInformation'] = $this->information;

        if (!empty($this->leadInformation)) {
            $data['LeadInformation'] = $this->leadInformation;
        }

        if (isset($data['ContactInformation']['Phones'])) {
            $data['ContactInformation']['Phones'][0]['Number'] = Str::limit($data['ContactInformation']['Phones'][0]['Number'], 10, '');
        }

        $response = $client->put('/gateway/v1/contact/' . $this->id, json_encode($data));

        $data['ContactId'] = $this->id;
        return new self($data);
    }
}
