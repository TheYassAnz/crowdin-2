<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
                'description' => 'English language - widely used internationally'
            ],
            [
                'name' => 'French',
                'code' => 'fr',
                'description' => 'French language - official language in 29 countries'
            ],
            [
                'name' => 'Spanish',
                'code' => 'es',
                'description' => 'Spanish language - second most spoken language by native speakers'
            ],
            [
                'name' => 'German',
                'code' => 'de',
                'description' => 'German language - most widely spoken language in the European Union'
            ],
            [
                'name' => 'Japanese',
                'code' => 'ja',
                'description' => 'Japanese language - official language of Japan'
            ],
        ];

        foreach ($languages as $lang) {
            $language = new Language();
            $language->setName($lang['name']);
            $language->setCode($lang['code']);
            $language->setDescription($lang['description']);
            $manager->persist($language);
        }

        $manager->flush();
    }
}