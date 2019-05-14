<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/data", name="app_profile_data")
     */
    public function profileData()
    {
        return $this->render('profile/data.html.twig', [
        ]);
    }

    /**
     * @Route("/profile/recipes/list", name="app_profile_recipe_list")
     */
    public function profileRecipeList()
    {
        //@TODO form list of current user recipes
        return $this->render('profile/recipeList.html.twig', [
        ]);
    }

}
