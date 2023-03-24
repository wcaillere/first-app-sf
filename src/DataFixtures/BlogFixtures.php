<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\CommentFactory;
use App\Factory\TagFactory;
use App\Factory\ThemeFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne();

        ThemeFactory::createOne(['themeName' => 'Politique']);
        ThemeFactory::createOne(['themeName' => 'Economie']);
        ThemeFactory::createOne(['themeName' => 'Société']);
        ThemeFactory::createOne(['themeName' => 'Loisirs']);
        ThemeFactory::createOne(['themeName' => 'Culture']);

        TagFactory::createOne(['TagName' => 'bien-être']);
        TagFactory::createOne(['TagName' => 'new Age']);
        TagFactory::createOne(['TagName' => 'sante']);
        TagFactory::createOne(['TagName' => 'Photographie']);
        TagFactory::createOne(['TagName' => 'Peinture']);

        ArticleFactory::createMany(
            50,
            function () {
                $randomDate = ArticleFactory::faker()->dateTimeBetween('-10 years');
                return [
                    'author'      => UserFactory::random(),
                    'theme'       => ThemeFactory::random(),
                    'tags'        => TagFactory::randomRange(0, 5),
                    'comments'    => CommentFactory::new([
                        'createdAt' => ArticleFactory::faker()->dateTimeBetween($randomDate)
                    ])->many(0, 10),
                    'createdAt'   => $randomDate,
                    'updatedAt'   => ArticleFactory::faker()->dateTimeBetween($randomDate),
                    'publishedAt' => ArticleFactory::faker()->dateTimeBetween($randomDate)
                ];
            }
        );
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
