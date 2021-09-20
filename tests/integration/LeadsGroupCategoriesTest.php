<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Integration;

use Kanvas\VinSolutions\Leads\GroupCategories;
use PhalconUnitTestCase;

class LeadsGroupCategoriesTest extends PhalconUnitTestCase
{
    public function testGetAll()
    {
        $types = GroupCategories::getAll();

        $this->assertIsArray($types);
    }
}
