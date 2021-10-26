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
     * Get all the leads for the given dealer.
     *
     * @param Dealer $dealer
     * @param User $user
     * @param array $params
     *
     * @return array
     */
    public static function getAll(Dealer $dealer, User $user, string $search, array $params = []) : array
    {
        $client = new Client($dealer->id, $user->id);
        $client->useDigitalShowRoomKey();

        $data = [];
        $data['DealerId'] = $dealer->id;
        $data['UserId'] = $user->id;

        $params = http_build_query($params);

        $response = $client->get(
            '/gateway/v1/contact?dealerId=' . $dealer->id . '&userId=' . $user->id . '&searchText=' . $search . '&' . $params,
        );

        return $response;
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
            $data['ContactInformation']['Phones'][0]['Number'] = Str::limit(
                preg_replace('/[^0-9]/', '', $data['ContactInformation']['Phones'][0]['Number']),
                10,
                ''
            );
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

        //clean information of emails
        if (!empty($this->emails)) {
            foreach ($this->emails as $key => $value) {
                if ($this->information['Emails'][$key]['EmailAddress'] !== $this->emails[$key]['EmailAddress']) {
                    $this->information['Emails'][$key] = $this->emails[$key];
                } else {
                    unset($this->information['Emails'][$key]);
                }
            }

            if (empty($this->information['Emails'])) {
                unset($this->information['Emails']);
            }
        }

        //clean information of phone
        if (!empty($this->phones)) {
            foreach ($this->phones as $key => $value) {
                if ($this->information['Phones'][$key]['Number'] !== $this->phones[$key]['Number']) {
                    $this->information['Phones'][$key] = $this->phones[$key];
                } else {
                    unset($this->information['Phones'][$key]);
                }
            }

            if (empty($this->information['Phones'])) {
                unset($this->information['Phones']);
            }
        }

        $data['ContactInformation'] = $this->information;

        if (!empty($this->leadInformation)) {
            $data['LeadInformation'] = $this->leadInformation;
        }

        if (!empty($this->phones) && isset($data['ContactInformation']['Phones'])) {
            $data['ContactInformation']['Phones'][0]['Number'] = Str::limit(
                preg_replace('/[^0-9]/', '', $data['ContactInformation']['Phones'][0]['Number']),
                10,
                ''
            );
        }

        $response = $client->put('/gateway/v1/contact/' . $this->id, json_encode($data));

        $data['ContactId'] = $this->id;
        return new self($data);
    }
}
