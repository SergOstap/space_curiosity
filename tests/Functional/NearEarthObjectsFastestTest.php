<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\NearEarthObjectFixture;
use App\Tests\FunctionalTestCase;

class NearEarthObjectsFastestTest extends FunctionalTestCase
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
            [null, '(2015 FF120)', 121757.27030153, false],
            [false, '(2015 FF120)', 121757.27030153, false],
            [true, '226514 (2003 UX34)', 107911.5080489, true],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @covers \App\Controller\NearEarthObjectsController::fastest()
     */
    public function testNearEarthObjectsFastest(?bool $isHazardous, string $name, float $speed, bool $isResultHazardous)
    {
        static::ensureKernelShutdown();
        $client = static::createClient();
        $params = [];
        if ($isHazardous !== null) {
            $params = ['hazardous' => $isHazardous ? 'true' : 'false'];
        }
        $client->request('GET', '/neo/fastest?' . http_build_query($params));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame($name, $response['name']);
        $this->assertSame($speed, $response['speed']);
        $this->assertSame($isResultHazardous, $response['is_hazardous']);
    }
}