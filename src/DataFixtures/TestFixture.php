<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\IngredientQuantity;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

class TestFixture extends Fixture implements FixtureGroupInterface
{

    protected $ingredients = [];

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {

        $this->ingredients = $manager->getRepository(Ingredient::class)->findAll();

        for ($i = 0; $i <= 50; $i++) {
            $recipe = $this->createRecipe($manager, $i);
            $manager->persist($recipe);
        }

        $manager->flush();
    }

    protected function createRecipe(ObjectManager $manager, int $index): Recipe
    {
        $recipe = new Recipe();
        $recipe->setName('recipe_test_' . $index);
        $recipe->setUser($this->getRandomUser($manager));
        $recipe->setDescription('recipe_test_description_' . $index);
        $recipe->setPreparationTime(rand(5, 90));
        $recipe->setType(rand(1, 5));
        $recipe->setIngredientQuantities($this->generateIngredients($manager, $recipe));

        return $recipe;
    }

    protected function getRandomUser(ObjectManager $manager): User
    {
        $users = $manager->getRepository(User::class)->findAll();

        return $users[array_rand($users)];
    }

    protected function generateIngredients(ObjectManager $manager, Recipe $recipe): ArrayCollection
    {
        $collection = new ArrayCollection();
        $count = array_rand([1, 3]);

        for ($i = 0; $i <= $count; $i++) {
            $iq = new IngredientQuantity();
            $iq->setIngredient($this->ingredients[array_rand($this->ingredients)]);
            $iq->setQuantity(rand(2, 10) . ' ' . $this->getRandomQuantitySuffix($manager));
            $iq->setRecipe($recipe);

            $manager->persist($iq);
            $collection->add($iq);
        }

        return $collection;
    }

    protected function getRandomQuantitySuffix(ObjectManager $manager): string
    {
        $suffixes = ['pieces', 'pcs.', 'glasses', 'dag', 'kg', 'pinches'];

        return $suffixes[array_rand($suffixes)];
    }


}
