<?php

namespace App\Controller;

use App\Form\PasswordChangeType;
use App\Form\ProfileDataType;
use App\Repository\RecipeRepository;
use App\Service\ClientManager;
use App\Service\FlashManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function sidebar(ClientManager $clientManager)
    {
        //sidebar is rendered in order given in array below
        $sidebarRoutes = [
            'profile_title_recipes_list' => 'app_profile_recipe_list',
            'profile_title_user_data' => 'app_profile_data_change',
            'profile_title_password_change' => 'app_profile_password_change',
            'profile_title_logout' => 'app_logout',
        ];

        return $this->render('profile/sidebar.html.twig', [
            'user' => $clientManager->getUser(),
            'sidebarRoutes' => $sidebarRoutes,
        ]);
    }

    /**
     * @Route("/profile/data", name="app_profile_data_change")
     */
    public function dataChange(Request $request, ClientManager $clientManager)
    {
        $form = $this->createForm(ProfileDataType::class, $clientManager->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientManager->saveUser($form->getData());

            $this->addFlash(FlashManager::FLASH_TYPE_SUCCESS, FlashManager::FLASH_MESSAGE_FORM_DATA_SAVED);
        }

        return $this->render('profile/data.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/change-password", name="app_profile_password_change")
     */
    public function passwordChange(Request $request, ClientManager $clientManager)
    {
        $form = $this->createForm(PasswordChangeType::class, $clientManager->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientManager->saveUserPassword($form->getData());

            $this->addFlash(FlashManager::FLASH_TYPE_SUCCESS, FlashManager::FLASH_MESSAGE_FORM_DATA_SAVED);
        }

        return $this->render('profile/passwordChange.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/recipes/list", name="app_profile_recipe_list")
     */
    public function recipeList(RecipeRepository $recipeRepository, ClientManager $clientManager)
    {
        $recipes = $recipeRepository->findBy(['user' => $clientManager->getUser()]); //@TODO pagination and use SearchManager for optimization

        return $this->render('profile/recipeList.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * @Route("/profile/recipes/create", name="app_profile_recipe_create")
     */
    public function recipeCreate()
    {
        return $this->render('profile/recipeCreate.html.twig', [
        ]);
    }

    /**
     * @Route("/profile/recipes/edit/{id}", name="app_profile_recipe_edit", requirements={"id"="\d+"})
     */
    public function recipeEdit(int $id, RecipeRepository $recipeRepository, ClientManager $clientManager)
    {
        $recipe = $recipeRepository->findOneBy(['user' => $clientManager->getUser(), 'id' => $id]);

        if (!$recipe) {
            throw new NotFoundHttpException();
        }

        return $this->render('profile/recipeEdit.html.twig', [
            'recipe' => $recipe,
        ]);
    }


}
