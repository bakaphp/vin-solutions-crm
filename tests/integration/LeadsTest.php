<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Faker\Factory;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Contact;
use Kanvas\VinSolutions\Leads\Lead;
use PhalconUnitTestCase;

class LeadsTest extends PhalconUnitTestCase
{
    public function testCreateLead()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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

    public function testStartShowRoom()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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

        $showroom = $lead->startShowRoom($dealer, $user);

        $this->assertInstanceOf(Lead::class, $showroom);
        $this->assertFalse((bool) $lead->isOnShowroom);
        $this->assertTrue((bool) $showroom->isOnShowroom);
    }

    public function testAddTradeVehicles()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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

        $showroom = $lead->addTradeIn(
            $dealer,
            $user,
            [
                'vin' => '2HGED6349LH506746',
                'year' => 1990,
                'make' => 'Honda',
                'model' => 'Civic',
                'mileage' => 1000,
                'value' => 1000,
                'condition' => 'UNKNOWN',
                'description' => 'good',
            ]
        );

        $this->assertIsArray($showroom);
    }

    public function testGetTradeVehicles()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

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

        $showroom = $lead->addTradeIn(
            $dealer,
            $user,
            [
                'vin' => '2HGED6349LH506746',
                'year' => 1990,
                'make' => 'Honda',
                'model' => 'Civic',
                'mileage' => 1000,
                'value' => 1000,
                'condition' => 'UNKNOWN',
                'description' => 'good',
            ]
        );

        $tradeIn = $lead->getTradeIn($dealer, $user);

        $this->assertIsArray($tradeIn);
        $this->assertTrue(count($tradeIn) > 0);
    }

    public function testGetAllLeads()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));

        $leads = Lead::getAll($dealer, $user);

        $this->assertIsArray($leads);
        $this->assertTrue(count($leads) > 0);
    }

    public function testGetAllLeadsPagination()
    {
        $dealer = Dealer::getById((int) getenv('VINSOLUTIONS_DEALER_ID'));
        $user = Dealer::getUser($dealer, (int) getenv('VINSOLUTIONS_USER_ID'));


        $params = [
            'leadStatusTypeId' => 1,
            'pageNumber' => 2,
            'pageSize' => 50,
        ];

        $leads = Lead::getAll($dealer, $user, $params);

        $this->assertIsArray($leads);
        $this->assertTrue(count($leads) > 0);
        $this->assertTrue($leads['PagingInfo']['PageSize'] === $params['pageSize']);
        $this->assertTrue($leads['PagingInfo']['PageNumber'] === $params['pageNumber']);
    }
}
