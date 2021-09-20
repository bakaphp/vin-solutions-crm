<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Dealers\User;
use PhalconUnitTestCase;

class DealerTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $dealers = Dealer::getAll();

        foreach ($dealers as $dealer) {
            $this->assertInstanceOf(Dealer::class, $dealer);
        }
    }

    public function testGetUsersFromDealer()
    {
        $dealerUsers = Dealer::getUsers(Dealer::getById(1));

        foreach ($dealerUsers as $user) {
            $this->assertInstanceOf(User::class, $user);
        }
    }

    public function testGetUserFromDealer()
    {
        $user = Dealer::getUser(Dealer::getById(1), 9);

        $this->assertInstanceOf(User::class, $user);
    }
}
