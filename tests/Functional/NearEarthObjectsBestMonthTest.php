<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\NearEarthObjectFixture;
use App\Tests\FunctionalTestCase;

class NearEarthObjectsBestMonthTest extends FunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->addFixture(new NearEarthObjectFixture());
        $this->executeFixtures();
    }

    public function dataProvider(): array
    {
        return [
            [null, '38', '2020-09'],
            [false, '38', '2020-09'],
            [true, '8', '2020-09'],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @covers \App\Controller\NearEarthObjectsController::fastest()
     */
    public function testNearEarthObjectsBestMonth(?bool $isHazardous, string $count, string $bestMonth)
    {
        static::ensureKernelShutdown();
        $client = static::createClient();
        $params = [];
        if ($isHazardous !== null) {
            $params = ['hazardous' => $isHazardous ? 'true' : 'false'];
        }
        $client->request('GET', '/neo/best-month?' . http_build_query($params));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame($count, $response['count']);
        $this->assertSame($bestMonth, $response['month']);
    }
}