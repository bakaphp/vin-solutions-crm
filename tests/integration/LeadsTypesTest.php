<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Kanvas\VinSolutions\Leads\Types;
use PhalconUnitTestCase;

class LeadsTypesTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $types = Types::getAll();

        $this->assertIsArray($types);
    }
}
