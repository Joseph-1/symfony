<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface


{

    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
        for ($i = 0; $i < 49; $i++) {
            $episode = new Episode();
            $faker  =  Faker\Factory::create('fr_FR');
            $episode->setTitle($faker->word);
            $episode->setNumber($faker->numberBetween(0, 49));
            $episode->setSynopsis($faker->text(200));
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0, 49)));

            $manager->persist($episode);

        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
