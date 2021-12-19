<?php

declare(strict_types=1);

namespace Kanvas\VinSolutions\Tests\Integration;

use Kanvas\VinSolutions\Client;
use PhalconUnitTestCase;

class RequestTest extends PhalconUnitTestCase
{
    public function testApiLogin()
    {
        $client = new Client(
            (int) getenv('VINSOLUTIONS_DEALER_ID'),
            (int) getenv('VINSOLUTIONS_USER_ID'),
        );
        $token = $client->auth();

        $this->assertIsArray($token);
        $this->assertArrayHasKey('access_token', $token);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertArrayHasKey('token_type', $token);
    }

    public function testApiGet()
    {
        $client = new  Client(
            (int) getenv('VINSOLUTIONS_DEALER_ID'),
            (int) getenv('VINSOLUTIONS_USER_ID'),
        );
        $response = $client->get('/gateway/v1/organization/dealers');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('Items', $response);
    }

    public function testPost()
    {
        $client = new  Client(
            (int) getenv('VINSOLUTIONS_DEALER_ID'),
            (int) getenv('VINSOLUTIONS_USER_ID'),
        );
        $json = '{
            "DealerId": ' . getenv('VINSOLUTIONS_DEALER_ID') . ',
            "UserId": ' . getenv('VINSOLUTIONS_USER_ID') . ',
            "ContactInformation": {
              "DealerId": ' . getenv('VINSOLUTIONS_DEALER_ID') . ',
              "Title": "string",
              "FirstName": "string",
              "MiddleName": "string",
              "LastName": "string",
              "NickName": "string",
              "CompanyName": "string",
              "CompanyType": "string"
            },
            "LeadInformation": {
              "CurrentSalesRepUserId": 0,
              "SplitSalesRepUserId": 0,
              "LeadSourceId": 0,
              "LeadTypeId": 0,
              "OnShowRoom": true,
              "SaleNotes": "string"
            }
          }';
        $response = $client->post('/gateway/v1/contact', $json);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ContactId', $response);
    }
}
