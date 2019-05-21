<?php


namespace App\Controller;


use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{

    /**
     * @Route("/recipe/{slug}", name="recipe")
     * @return Response
     */
    public function recipe(string $slug, RecipeRepository $recipeRepository)
    {
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);

        if (!$recipe) {
            throw new NotFoundHttpException();
        }

        return $this->render('recipe/recipe.html.twig',
            [
                'recipe' => $recipe,
            ]
        );
    }
}