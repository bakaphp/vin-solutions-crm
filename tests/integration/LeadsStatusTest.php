<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Kanvas\VinSolutions\Leads\Status;
use PhalconUnitTestCase;

class LeadsSourcesTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $types = Status::getAll();

        $this->assertIsArray($types);
    }
}
