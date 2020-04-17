<?php
/**
 * This file tests the SendGrid Client
 */

namespace SendGrid\Tests\Unit;

use SendGrid\Tests\BaseTestClass;


/**
 * This class tests the Twilio SendGrid Client
 *
 * @package SendGrid\Tests\Unit
 */
class SendGridTest extends BaseTestClass
{
    /**
     * Test that we can connect to the Twilio SendGrid API
     */
    public function testCanConnectToSendGridApi()
    {
        $sg = new \SendGrid(self::$apiKey);
        $headers = [
            'Authorization: Bearer ' . self::$apiKey,
            'User-Agent: sendgrid/' . $sg->version . ';php',
            'Accept: application/json'
        ];

        $this->assertEquals(
            $sg->client->getHost(),
            'https://api.sendgrid.com',
            '/v3'
        );
        $this->assertEquals(
            $sg->client->getHeaders(),
            $headers
        );
        $this->assertEquals($sg->client->getVersion(), '/v3');

        $sg2 = new \SendGrid(self::$apiKey, ['host' => 'https://api.test.com']);
        $this->assertEquals($sg2->client->getHost(), 'https://api.test.com');

        $sg3 = new \SendGrid(self::$apiKey, ['curl' => ['foo' => 'bar']]);
        $this->assertEquals(['foo' => 'bar'], $sg3->client->getCurlOptions());

        $sg4 = new \SendGrid(
            self::$apiKey,
            ['curl' => [CURLOPT_PROXY => '127.0.0.1:8000']]
        );
        $this->assertEquals(
            $sg4->client->getCurlOptions(),
            [10004 => '127.0.0.1:8000']
        );

        $subuser = 'abcxyz@this.is.a.test.subuser';
        $headers[] = 'On-Behalf-Of: ' . $subuser;
        $sg5 = new \SendGrid(
            self::$apiKey,
            ['impersonateSubuser' => $subuser]
        );
        $this->assertSame(
            $headers,
            $sg5->client->getHeaders()
        );
    }
}
