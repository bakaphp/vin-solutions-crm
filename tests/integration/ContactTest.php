<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

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
                'Emails' => [
                    [
                        'EmailId' => 0,
                        'EmailAddress' => $faker->email,
                        'EmailType' => 'primary'
                    ]
                ],
                'Phones' => [
                    [
                        'PhoneId' => 0,
                        'PhoneType' => 'Cell',
                        'Number' => '8093505188000'
                    ]
                ]
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
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));

        $contact = LeadsContact::create(
            $dealer,
            Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID')),
            $contact
        );

        $this->assertInstanceOf(LeadsContact::class, $contact);
        $this->assertTrue($contact->id > 0);
    }

    public function testGetById()
    {
        $faker = Factory::create();

        $contact = [
            'ContactInformation' => [
                'title' => $faker->title(),
                'FirstName' => $faker->firstName(),
                'LastName' => $faker->lastName,
                'CompanyName' => $faker->company,
                'CompanyType' => $faker->companySuffix,
                'Emails' => [
                    [
                        'EmailId' => 0,
                        'EmailAddress' => $faker->email,
                        'EmailType' => 'primary'
                    ]
                ],
                'Phones' => [
                    [
                        'PhoneId' => 0,
                        'PhoneType' => 'Cell',
                        'Number' => '8093505188000'
                    ]
                ]
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
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

        $contact = LeadsContact::create(
            $dealer,
            $user,
            $contact
        );

        $contactInfo = LeadsContact::getById($dealer, $user, $contact->id);

        $this->assertInstanceOf(LeadsContact::class, $contactInfo);
        $this->assertTrue($contactInfo->id > 0);
        $this->assertIsArray($contactInfo->emails);
        $this->assertIsArray($contactInfo->phones);
    }

    public function testUpdateContact()
    {
        $faker = Factory::create();

        $contact = [
            'ContactInformation' => [
                'title' => $faker->title(),
                'FirstName' => $faker->firstName(),
                'LastName' => $faker->lastName,
                'CompanyName' => $faker->company,
                'CompanyType' => $faker->companySuffix,
                'Emails' => [
                    [
                        'EmailId' => 0,
                        'EmailAddress' => $faker->email,
                        'EmailType' => 'primary'
                    ]
                ],
                'Phones' => [
                    [
                        'PhoneId' => 0,
                        'PhoneType' => 'Cell',
                        'Number' => '8093505188000'
                    ]
                ]
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

        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

        $contact = LeadsContact::create(
            $dealer,
            $user,
            $contact
        );

        $contactInfo = LeadsContact::getById($dealer, $user, $contact->id);
        $contactInfo->information['FirstName'] = $faker->firstName();
        $contactInfo->information['LastName'] = $faker->lastName;

        $contactUpdated = $contactInfo->update($dealer, $user);

        $contactInfoNew = LeadsContact::getById($dealer, $user, $contactUpdated->id);

        $this->assertInstanceOf(LeadsContact::class, $contactInfo);
        $this->assertTrue($contactInfo->id > 0);

        $this->assertEquals($contactInfo->information['FirstName'], $contactInfoNew->information['FirstName']);
        $this->assertEquals($contactInfo->information['LastName'], $contactInfoNew->information['LastName']);
        $this->assertEquals($contactInfo->emails[0]['EmailAddress'], $contactInfoNew->emails[0]['EmailAddress']);
    }

    public function testGetAllContacts()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

        $contacts = LeadsContact::getAll($dealer, $user, 'gmail');

        $this->assertIsArray($contacts);
        $this->assertTrue(count($contacts) > 0);
    }

    public function testGetAllContactsWithParams()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

        $params = [
            'pageNumber' => 2,
            'pageSize' => 50,
        ];

        $contacts = LeadsContact::getAll(
            $dealer,
            $user,
            'gmail',
            $params,
        );

        $this->assertIsArray($contacts);
        $this->assertTrue(count($contacts) === $params['pageSize']);
    }
}
