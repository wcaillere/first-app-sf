<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des auteurs
        $author = new Author();
        $author
            ->setFirstName('Pablo')
            ->setLastName('Neruda');
        $manager->persist($author);
        $this->addReference('author_1', $author);

        $author = new Author();
        $author
            ->setFirstName('Jorge Luis')
            ->setLastName('Borges');
        $manager->persist($author);
        $this->addReference('author_2', $author);


        $author = new Author();
        $author
            ->setFirstName('Emily')
            ->setLastName('Dickinson');
        $manager->persist($author);
        $this->addReference('author_3', $author);


        // Création des éditeurs
        $publisher = new Publisher();
        $publisher->setName("Hachette");
        $manager->persist($publisher);
        $this->addReference('publisher_1', $publisher);


        $publisher = new Publisher();
        $publisher->setName("Gallimard");
        $manager->persist($publisher);
        $this->addReference('publisher_2', $publisher);


        $publisher = new Publisher();
        $publisher->setName("Pocket");
        $manager->persist($publisher);
        $this->addReference('publisher_3', $publisher);


        $manager->flush();
    }
}
