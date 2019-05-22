<?php


namespace App\Controller;


use App\Service\RecipeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    /**
     * @Route("/search/{page}/{phrase}/{ingredients}", name="searchPhraseIngredients", requirements={"page"="\d+"})
     * @Route("/search-ingredients/{page}/{ingredients}", name="searchIngredients", requirements={"page"="\d+"})
     * @Route("/search-phrase/{page}/{phrase}", name="searchPhrase", requirements={"page"="\d+"})
     * @return Response
     */
    public function search(int $page, ?string $phrase, ?string $ingredients, RecipeManager $recipeManager)
    {

        $recipeManager->setPhrase($phrase);
        $recipeManager->setIngredients(explode(',', $ingredients));

        $recipes = $recipeManager->search($page);

        return $this->render('search/search.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}