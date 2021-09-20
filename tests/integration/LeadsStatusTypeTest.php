<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Integration;

use Kanvas\VinSolutions\Leads\StatusTypes;
use PhalconUnitTestCase;

class LeadsStatusTypeTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $types = StatusTypes::getAll();

        $this->assertIsArray($types);
    }
}
