<?php

namespace App\Controller;

use App\Form\PasswordChangeType;
use App\Form\ProfileDataType;
use App\Repository\RecipeRepository;
use App\Service\ClientManager;
use App\Service\FlashManager;
use App\Service\RecipeManager;
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
            'profile_title_recipes_list' => ['routeName' => 'app_profile_recipe_list', 'routeParams' => ['page' => 1]],
            'profile_title_user_data' => ['routeName' => 'app_profile_data_change', 'routeParams' => []],
            'profile_title_password_change' => ['routeName' => 'app_profile_password_change', 'routeParams' => []],
            'profile_title_logout' => ['routeName' => 'app_logout', 'routeParams' => []],
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
            $clientManager->saveUserPassword($form->getData(), $form['plainPassword']->getData());

            $this->addFlash(FlashManager::FLASH_TYPE_SUCCESS, FlashManager::FLASH_MESSAGE_FORM_DATA_SAVED);
        }

        return $this->render('profile/passwordChange.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/recipes/list/{page}", name="app_profile_recipe_list")
     */
    public function recipeList(int $page, ClientManager $clientManager, RecipeManager $recipeManager)
    {
        $pagination = $recipeManager->getUserRecipes($clientManager->getUser(), $page);

        return $this->render('profile/recipeList.html.twig', [
            'pagination' => $pagination,
        ]);

        return $this->render('profile/recipeList.html.twig', [
            'pagination' => $pagination,
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
