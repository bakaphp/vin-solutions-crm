<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Integration;

use Faker\Factory;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Contact;
use Kanvas\VinSolutions\Leads\Lead;
use PhalconUnitTestCase;

class LeadsTest extends PhalconUnitTestCase
{
    public function testCreateLead()
    {
        $dealer = Dealer::getById(1);
        $user = Dealer::getUser($dealer, 9);
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

        $contact = Contact::create(
            $dealer,
            $user,
            $contact
        );


        $lead = [
            'leadSource' => 55694,
            'leadType' => 'INTERNET',
            'contact' => $contact->id,
            'isHot' => true
        ];

        $newLead = Lead::create($dealer, $user, $lead);

        $this->assertInstanceOf(Lead::class, $newLead);
        $this->assertTrue($newLead->id > 0);
    }

    public function testGetById()
    {
        $dealer = Dealer::getById(1);
        $user = Dealer::getUser($dealer, 9);
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

        $contact = Contact::create(
            $dealer,
            $user,
            $contact
        );


        $lead = [
            'leadSource' => 55694,
            'leadType' => 'INTERNET',
            'contact' => $contact->id,
            'isHot' => true
        ];

        $newLead = Lead::create($dealer, $user, $lead);

        $lead = Lead::getById($dealer, $user, $newLead->id);

        $this->assertInstanceOf(Lead::class, $newLead);
        $this->assertEquals($newLead->id, $lead->id);
    }

    public function testUpdateLead()
    {
        $dealer = Dealer::getById(1);
        $user = Dealer::getUser($dealer, 9);
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

        $contact = Contact::create(
            $dealer,
            $user,
            $contact
        );

        $lead = [
            'leadSource' => 55694,
            'leadType' => 'INTERNET',
            'contact' => $contact->id,
            'isHot' => true
        ];

        $newLead = Lead::create($dealer, $user, $lead);
        $lead = Lead::getById($dealer, $user, $newLead->id);

        $copyLead = $lead;

        $newLead->isHot = 0;
        $updateLead = $newLead->update($dealer, $user);

        $lead = Lead::getById($dealer, $user, $newLead->id);


        $this->assertInstanceOf(Lead::class, $updateLead);
        $this->assertNotEquals($copyLead->isHot, $lead->isHot);
    }

    public function testAddNotes()
    {
        $dealer = Dealer::getById(1);
        $user = Dealer::getUser($dealer, 9);
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

        $contact = Contact::create(
            $dealer,
            $user,
            $contact
        );

        $lead = [
            'leadSource' => 55694,
            'leadType' => 'INTERNET',
            'contact' => $contact->id,
            'isHot' => true
        ];

        $newLead = Lead::create($dealer, $user, $lead);
        $lead = Lead::getById($dealer, $user, $newLead->id);

        $updateLead = $newLead->addNotes($dealer, $user, 'test notes');
        $this->assertInstanceOf(Lead::class, $updateLead);
    }
}
