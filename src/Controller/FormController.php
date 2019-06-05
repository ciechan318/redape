<?php


namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\ClientManager;
use App\Service\RecipeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/form/recipe/{id}", name="app_form_recipe")
     */
    public function recipe(RequestStack $requestStack, RecipeRepository $recipeRepository, RecipeManager $recipeManager, ClientManager $clientManager, ?int $id = null)
    {
        //since this action (and its form) is always embedded we need to use master request, not (sub)request created by Symfony for this action
        $request = $requestStack->getMasterRequest();

        if (!$id) {
            $recipe = new Recipe();
            $recipe->setUser($clientManager->getUser());
        } else {
            $recipe = $recipeRepository->findOneBy(['user' => $clientManager->getUser(), 'id' => $id]);

            if (!$recipe) {
                throw new NotFoundHttpException();
            }
        }

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);


        $isValid = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeManager->saveRecipe($form->getData());

            $isValid = true;
        }

        return $this->render('form/recipeType.html.twig', [
            'form' => $form->createView(),
            'isValid' => $isValid
        ]);
    }
}