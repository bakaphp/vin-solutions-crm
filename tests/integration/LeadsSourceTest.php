<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Source;
use PhalconUnitTestCase;

class LeadsSourceTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));


        $sources = Source::getAll($dealer, $user);

        foreach ($sources as $source) {
            $this->assertInstanceOf(Source::class, $source);
        }
    }

    public function testGetById()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));


        $source = Source::getById($dealer, $user, 760809);

        $this->assertInstanceOf(Source::class, $source);
    }
}
