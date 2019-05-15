<?php

namespace App\Controller;

use App\Form\ProfileDataType;
use App\Service\ClientManager;
use App\Service\FlashManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/data", name="app_profile_data")
     */
    public function profileData(Request $request, ClientManager $clientManager)
    {
        $form = $this->createForm(ProfileDataType::class, $clientManager->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientManager->saveUser($form->getData());

            $this->addFlash(FlashManager::FLASH_TYPE_SUCCESS,FlashManager::FLASH_MESSAGE_FORM_DATA_SAVED);
        }

        return $this->render('profile/data.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/change-password", name="app_profile_password")
     */
    public function profilePassword()
    {
        return $this->render('profile/passwordChange.html.twig', [
        ]);
    }

    /**
     * @Route("/profile/recipes/list", name="app_profile_recipe_list")
     */
    public function profileRecipeList()
    {
        //@TODO list of current user's recipes
        return $this->render('profile/recipeList.html.twig', [
        ]);
    }

    /**
     * @Route("/profile/recipes/create", name="app_profile_recipe_create")
     */
    public function profileRecipeCreate()
    {
        //@TODO form create recipe
        return $this->render('profile/recipeCreate.html.twig', [
        ]);
    }

    /**
     * @Route("/profile/recipes/edit", name="app_profile_recipe_edit")
     */
    public function profileRecipeEdit()
    {
        //@TODO form edit recipe
        return $this->render('profile/recipeEdit.html.twig', [
        ]);
    }

}
