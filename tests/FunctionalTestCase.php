<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class FunctionalTestCase extends WebTestCase
{
    private ?ContainerAwareLoader $fixtureLoader = null;
    private ?ORMExecutor $fixtureExecutor = null;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();

        $app = new Application(self::$kernel);
        $app->setAutoExit(false);
        $app->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:database:drop',
                    '--if-exists' => '1',
                    '--force' => '1',
                ]
            ),
            new BufferedOutput()
        );
        $app->run(new ArrayInput(['command' => 'doctrine:database:create']), new BufferedOutput());
        $app->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:migrations:migrate',
                    '--no-interaction'
                ]
            ),
            new BufferedOutput()
        );
    }

    protected function addFixture(FixtureInterface $fixture): self
    {
        $this->getFixtureLoader()->addFixture($fixture);

        return $this;
    }

    protected function executeFixtures(): void
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    private function getFixtureLoader(): ContainerAwareLoader
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }

        return $this->fixtureLoader;
    }

    private function getFixtureExecutor(): ORMExecutor
    {
        if (!$this->fixtureExecutor) {
            /** @var EntityManager $entityManager */
            $entityManager = $this->getDoctrine()->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }

        return $this->fixtureExecutor;
    }

    /**
     * @return Registry
     */
    protected function getDoctrine(): Registry
    {
        return self::$kernel->getContainer()->get('doctrine');
    }
}
