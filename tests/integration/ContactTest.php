<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Integration;

use Faker\Factory;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Contact as LeadsContact;
use PhalconUnitTestCase;

class ContactTest extends PhalconUnitTestCase
{
    public function testCreate()
    {
        $faker = Factory::create();

        $contact = [
            'ContactInformation' => [
                'title' => $faker->title(),
                'FirstName' => $faker->firstName(),
                'LastName' => $faker->lastName,
                'CompanyName' => $faker->company,
                'CompanyType' => $faker->companySuffix,
            ],
            'LeadInformation' => [
                'CurrentSalesRepUserId' => 0,
                'SplitSalesRepUserId' => 0,
                'LeadSourceId' => 0,
                'LeadTypeId' => 0,
                'OnShowRoom' => false,
                'SaleNotes' => '',
            ]
        ];
        $dealer = Dealer::getById(1);

        $contact = LeadsContact::create(
            $dealer,
            Dealer::getUser($dealer, 9),
            $contact
        );

        $this->assertInstanceOf(LeadsContact::class, $contact);
        $this->assertTrue($contact->id > 0);
    }
}
