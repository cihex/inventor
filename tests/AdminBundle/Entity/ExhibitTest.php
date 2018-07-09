<?php

namespace Tests\AdminBundle\Entity;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Exhibit;
use Tests\AdminBundle\DatabaseTestCase;

/**
 * Class ExhibitTest
 * @package Tests\AdminBundle\Entity
 */
class ExhibitTest extends DatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();

    }

    /**
     * @param string $exhibitName
     * @param string $categoryName
     * @param string $categoryAlias
     * @param $exceptedNumber
     * @dataProvider exhibitDataProvider
     */
    public function testGetNumber(string $exhibitName, string $categoryName = null, string $categoryAlias = null, $exceptedNumber)
    {
        $exhibit = $this->addExhibitWithCategory($exhibitName, $categoryName, $categoryAlias);
        $this->assertEquals($exceptedNumber, $exhibit->getNumber());
    }

    /**
     * @return array
     */
    public function exhibitDataProvider(): array
    {
        return [
            ['Nazwa1', 'Kategoria1', 'KAT', 'KAT/00001'],
            ['Przedmiot', 'Maszyny Rolnicze', 'MR', 'MR/00001'],
            ['Nazwa3', 'Kategoria3', 'DD', 'DD/00001'],
            ['Nazwa6', null, null, 'B/K/00001'],
        ];
    }

    /**
     * @param string $exhibitName
     * @param string $categoryName
     * @param string|null $categoryAlias
     * @return Exhibit
     */
    protected function addExhibitWithCategory(string $exhibitName, string $categoryName = null, string $categoryAlias = null): \AdminBundle\Entity\Exhibit
    {
        $em = $this->getEntityManager();
        if ($categoryName !== null) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setAlias($categoryAlias);
            $em->persist($category);
            $em->flush();
        }
        $exhibit = new Exhibit();
        $exhibit->setName($exhibitName);
        if ($categoryName !== null) {
            $exhibit->setCategory($category);
        }
        $em->persist($exhibit);
        $em->flush();
        return $exhibit;
    }
}