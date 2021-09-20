<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Integration;

use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Source;
use PhalconUnitTestCase;

class LeadsSourceTest extends PhalconUnitTestCase
{
    public function testGetById()
    {
        $dealer = Dealer::getById(1);
        $user = Dealer::getUser($dealer, 9);

        $source = Source::getById($dealer, $user, 760809);

        $this->assertInstanceOf(Source::class, $source);
    }
}
