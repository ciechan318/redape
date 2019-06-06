<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\Entity\Translation;

class AppFixture extends Fixture implements FixtureGroupInterface
{

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    public static function getGroups(): array
    {
        return ['prod'];
    }

    public function load(ObjectManager $manager)
    {
        $this->translationRepository = $manager->getRepository(Translation::class);
        $settingsAbout = $this->createAboutDemoEntry();

        $manager->persist($settingsAbout);

        $manager->flush();
    }

    protected function createAboutDemoEntry(): Settings
    {
        $about = new Settings();
        $about->setType(Settings::TYPE_ABOUT);
        $about->setBody('>> [WIP] About R.E.D.A.P.E. <<'); //in english - default locale

        $this->translationRepository->translate($about, 'body', 'pl', '>> [WIP] O R.E.D.A.P.E. <<');

        return $about;
    }

}
