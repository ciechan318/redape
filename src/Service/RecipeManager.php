<?php

namespace App\Service;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;


class RecipeManager
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveRecipe(Recipe $recipe): void
    {
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
    }

}
