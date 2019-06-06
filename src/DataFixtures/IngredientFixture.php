<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\Entity\Translation;

class IngredientFixture extends Fixture implements FixtureGroupInterface
{

    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    public static function getGroups(): array
    {
        return ['prod', 'ingredient'];
    }

    public function load(ObjectManager $manager)
    {
        $this->translationRepository = $manager->getRepository(Translation::class);

        foreach ($this->getIngredients() as $ingredient) {
            $manager->persist($ingredient);
        }

        $manager->flush();
    }

    protected function getIngredients(): array
    {
        $result = [];

        foreach ($this->getIngredientsNames() as $ingName) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingName['en']); //in english - default locale
            $ingredient->setUpdatedAt(new \DateTime());

            $this->translationRepository->translate($ingredient, 'name', 'pl', $ingName['pl']);
            $result[] = $ingredient;
        }

        return $result;
    }

    protected function getIngredientsNames(): array
    {
        return [
            [
                'en' => 'Pineapple',
                'pl' => 'Ananas',
            ],
            [
                'en' => 'Pear',
                'pl' => 'Gruszka',
            ],
            [
                'en' => 'Tomato',
                'pl' => 'Pomidor',
            ],
            [
                'en' => 'Cucumber',
                'pl' => 'Ogórek',
            ],
            [
                'en' => 'Pepper',
                'pl' => 'Papryka',
            ],
            [
                'en' => 'Pepper',
                'pl' => 'Pieprz',
            ],
            [
                'en' => 'Avocado',
                'pl' => 'Awokado',
            ],
            [
                'en' => 'Salt',
                'pl' => 'Sól',
            ],
            [
                'en' => 'Mint',
                'pl' => 'Mięta',
            ],
            [
                'en' => 'Onion',
                'pl' => 'Cebula',
            ],
            [
                'en' => 'Mushroom',
                'pl' => 'Pieczarka',
            ],
            [
                'en' => 'Celery',
                'pl' => 'Seler',
            ],
            [
                'en' => 'Lovage',
                'pl' => 'Lubczyk',
            ],
            [
                'en' => 'Thyme',
                'pl' => 'Tymianek',
            ],
            [
                'en' => 'Curry',
                'pl' => 'Curry',
            ],
            [
                'en' => 'Cumin',
                'pl' => 'Kminek',
            ],
            [
                'en' => 'Chive',
                'pl' => 'Szczypiorek',
            ],
            [
                'en' => 'Parsley',
                'pl' => 'Pietruszka',
            ],
            [
                'en' => 'Chicken',
                'pl' => 'Kurczak',
            ],
            [
                'en' => 'Chicken fillet',
                'pl' => 'Filet z kurczaka',
            ],
            [
                'en' => 'Coffee',
                'pl' => 'Kawa',
            ],
            [
                'en' => 'Tea',
                'pl' => 'Herbata',
            ],
            [
                'en' => 'Pork',
                'pl' => 'Wieprzowina',
            ],
            [
                'en' => 'Pork shoulder',
                'pl' => 'Łopatka wieprzowa',
            ],
            [
                'en' => 'Pork neck',
                'pl' => 'Karkówka',
            ],
            [
                'en' => 'Sugar',
                'pl' => 'Cukier',
            ],
            [
                'en' => 'Chicken sausage',
                'pl' => 'Kiełbasa drobiowa',
            ],
            [
                'en' => 'Bacon',
                'pl' => 'Boczek',
            ],
            [
                'en' => 'Ham',
                'pl' => 'Szynka',
            ],
            [
                'en' => 'Watermelon',
                'pl' => 'Arbuz',
            ],
            [
                'en' => 'Zucchini',
                'pl' => 'Cukinia',
            ],
            [
                'en' => 'Feta cheese',
                'pl' => 'Ser feta',
            ],
            [
                'en' => 'Cheddar',
                'pl' => 'Cheddar',
            ],
            [
                'en' => 'Bread',
                'pl' => 'Chleb',
            ],
            [
                'en' => 'Olive',
                'pl' => 'Oliwki',
            ],
            [
                'en' => 'Oil',
                'pl' => 'Olej',
            ],
        ];
    }

}
