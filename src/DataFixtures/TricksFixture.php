<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Sample data
 * This fixtures will add some tricks and commments
 *
 * Class TricksFixture
 * @package App\DataFixtures
 */
class TricksFixture extends Fixture implements FixtureGroupInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

    }

    public static function getGroups(): array
    {
        return ['sample_data'];
    }
}