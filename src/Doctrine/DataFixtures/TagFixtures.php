<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $tag1 = (new Tag())
            ->setName('Action');
        $tag2 = (new Tag())
            ->setName('Aventure');
        $tag3 = (new Tag())
            ->setName('RPG');

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($tag3);
        $manager->flush();
    }
}
