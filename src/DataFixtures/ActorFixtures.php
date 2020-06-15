<?php


namespace App\DataFixtures;


use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture


{
    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
    ];

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        // TODO: Implement load() method.
       // foreach (self::ACTORS as $key => $actorName) {
        for ($i = 0; $i < 49; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);

            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);

        }

        $manager->flush();

    }
}
