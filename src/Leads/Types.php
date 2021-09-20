<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Leads;

use Kanvas\VinSolutions\Client;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;

class Types
{

    /**
     * Get all lead source.
     *
     * @param Dealer $dealer
     * @param User $user
     *
     * @return array
     */
    public static function getAll() : array
    {
        $client = new Client(0, 0);

        $response = $client->get('/leadTypes', [
            'headers' => [
                'Accept' => 'application/vnd.coxauto.v1+json'
            ]
        ]);

        return $response;
    }
}
