<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Leads;

use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Contact
{
    public int $id;
    public array $information = [];
    public array $customerConsent = [];
    public array $dealerTeam = [];
    public array $smsPreferences = [];

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
        $this->smsPreferences = $data['SmsPreferences'] ?? [];
        $this->dealerTeam = $data['DealerTeam'] ?? [];
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

        $response = $client->post('/gateway/v1/contact', json_encode($data));

        return new self($response);
    }
}
