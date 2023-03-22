<?php

namespace App\Service;

use Faker\Factory;

class AppService
{
    public function getName(): string
    {
        return 'App service';
    }

    public function getAuthor(int $id): array
    {
        $data = array_filter($this->getAuthorList(), function ($item) use ($id) {
            return $item["id"] == $id;
        });

        $faker = Factory::create();

        if (count($data) > 0) {
            $data = $data[array_key_first($data)];
            $data['bio'] = $faker->text(1000);
            return $data;
        } else {
            return [];
        }
    }

    public function getAuthorList(): array
    {
        return [
            [
                'name' => 'Hugo',
                'firstName' => 'Victor',
                'id' => 1
            ],
            [
                'name' => 'Despentes',
                'firstName' => 'Virginie',
                'id' => 2
            ],
            [
                'name' => 'Sand',
                'firstName' => 'Georges',
                'id' => 3
            ],
            [
                'name' => 'Le Guin',
                'firstName' => 'Ursula',
                'id' => 4
            ],
            [
                'name' => 'Henlein',
                'firstName' => 'Robert',
                'id' => 5
            ]
        ];
    }

    public function getRoutesForNavbar(): array
    {
        return [
            [
                'label' => 'Liste des auteurs',
                'route' => 'author_index'
            ],
            [
                'label' => 'Editeurs',
                'route' => 'publisher_index'
            ],
            [
                'label' => 'Livres',
                'route' => 'book_index'
            ]
        ];
    }
}