<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
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

    public function toggleFavourite(Recipe $recipe, User $user): void
    {
        if ($recipe->isLikedBy($user)) {
            $recipe->setLikes($recipe->getLikes() - 1);
            $recipe->getUserFavourites()->removeElement($user);
            $user->getRecipeFavourites()->removeElement($recipe);
        } else {
            $recipe->setLikes($recipe->getLikes() + 1);
            $recipe->getUserFavourites()->add($user);
            $user->getRecipeFavourites()->add($recipe);
        }

        $this->entityManager->persist($user);
        $this->saveRecipe($recipe);
    }

    public function saveRecipe(Recipe $recipe): void
    {
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
    }

}
