<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Faker\Factory;
use Kanvas\VinSolutions\Dealers\Dealer;
use Kanvas\VinSolutions\Leads\Contact;
use Kanvas\VinSolutions\Leads\Lead;
use Kanvas\VinSolutions\Vehicles\TradeIn;
use PhalconUnitTestCase;

class VehicleTradeInTest extends PhalconUnitTestCase
{
    public function testGetLeadsTradeIn()
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
        $tradeIn = TradeIn::getByLeadId($dealer, $user, $newLead->id);

        $this->assertTrue($tradeIn instanceof TradeIn);
        $this->assertTrue(property_exists($tradeIn, 'items'));
        $this->assertTrue($tradeIn->count > 0);
    }
}
