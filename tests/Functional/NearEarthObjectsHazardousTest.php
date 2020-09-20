<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\NearEarthObjectFixture;
use App\Tests\FunctionalTestCase;

class NearEarthObjectsHazardousTest extends FunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->addFixture(new NearEarthObjectFixture());
        $this->executeFixtures();
    }

    /**
     * @covers \App\Controller\NearEarthObjectsController::hazardous()
     */
    public function testNearEarthObjectsHazardous()
    {
        static::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/neo/hazardous');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame(10, $response['limit']);
        $this->assertSame(0, $response['offset']);
        $this->assertSame(8, $response['total']);
        $this->assertCount(8, $response['data']);
        $this->assertArrayHasKey('id', $response['data'][0]);
        $this->assertArrayHasKey('approach_date', $response['data'][0]);
        $this->assertArrayHasKey('reference', $response['data'][0]);
        $this->assertArrayHasKey('name', $response['data'][0]);
        $this->assertArrayHasKey('speed', $response['data'][0]);
        $this->assertTrue($response['data'][0]['is_hazardous']);
    }
}