<?php
declare(strict_types=1);

namespace Kanvas\VinSolutions\Dealers;

use Kanvas\VinSolutions\Client;

class Dealer
{
    public int $id;
    public string $name;
    public string $city;
    public string $state;

    /**
     * Initialize a dealer.
     */
    public function __construct(int $id, string $name, string $city, string $state)
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->state = $state;
    }

    /**
     * Get all the dealers who we have permission for the current app Key.
     *
     * @return array
     */
    public static function getAll() : array
    {
        $client = new Client(0, 0);
        $response = $client->get('/gateway/v1/organization/dealers');

        $dealers = [];
        if (count($response)) {
            foreach ($response['Items'] as $item) {
                $dealers[$item['DealerId']] = new Dealer($item['DealerId'], $item['Name'], $item['City'], $item['State']);
            }
        }

        return $dealers;
    }

    /**
     * Get a dealer by its ID.
     *
     * @param int $id
     *
     * @return Dealer
     */
    public static function getById(int $id) : Dealer
    {
        return self::getAll()[$id];
    }

    /**
     * Get all users fro the given dealer.
     *
     * @param Dealer $dealer
     *
     * @return array
     */
    public static function getUsers(Dealer $dealer) : array
    {
        $client = new Client($dealer->id, 0);
        $response = $client->get('/gateway/v1/tenant/user?dealerId=' . $dealer->id);

        $users = [];
        if (count($response)) {
            foreach ($response as $item) {
                $users[] = new User($item);
            }
        }

        return $users;
    }

    /**
     * Get a individual user by its ID.
     *
     * @param Dealer $dealer
     * @param int $userId
     *
     * @return User
     */
    public static function getUser(Dealer $dealer, int $userId) : User
    {
        $client = new Client($dealer->id, 0);
        $response = $client->get('/gateway/v1/tenant/user/id/' . $userId . '?dealerId=' . $dealer->id);

        return  new User($response);
    }
}
