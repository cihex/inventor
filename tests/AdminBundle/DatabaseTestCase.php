<?php

namespace Tests\AdminBundle;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DatabaseTestCase
 * @package Tests\AdminBundle
 */
class DatabaseTestCase extends KernelTestCase
{
    /**
     * @var KernelInterface
     */
    //protected $kernel;

    protected function setUp()
    {
        parent::setUp();
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);
    }

    /**
     * @return ObjectManager
     */
    protected function getEntityManager(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return Registry
     */
    protected function getDoctrine(): Registry
    {
        return self::$kernel->getContainer()->get('doctrine');
    }

    /**
     * @param $key
     * @return object
     */
    protected function get($key)
    {
        return self::$kernel->getContainer()->get($key);
    }

    protected function tearDown()
    {
        DatabasePrimer::drop(self::$kernel);
        parent::tearDown();
    }

}