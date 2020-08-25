<?php


namespace App\Controller;


use App\Repository\RecipeRepository;
use App\Service\ClientManager;
use App\Service\RecipeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RecipeController extends AbstractController
{

    use TargetPathTrait;

    /**
     * @Route("/recipe/{slug}", name="recipe")
     * @return Response
     */
    public function recipe(string $slug, RecipeRepository $recipeRepository, ClientManager $clientManager)
    {
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);

        if (!$recipe) {
            throw new NotFoundHttpException();
        }

        return $this->render('recipe/recipe.html.twig',
            [
                'recipe' => $recipe,
                'user' => $clientManager->getUser(),
            ]
        );
    }

    /**
     * @Route("/recipe/{slug}/like", name="toggleRecipeLike", methods={"POST"})
     * @param string $slug
     * @param Request $request
     * @param FirewallMap $firewallMap
     * @param SessionInterface $session
     * @param RecipeRepository $recipeRepository
     * @param RecipeManager $recipeManager
     * @param ClientManager $clientManager
     * @return Response
     */
    public function toggleRecipeLike(
        string $slug,
        Request $request,
        FirewallMap $firewallMap,
        SessionInterface $session,
        RecipeRepository $recipeRepository,
        RecipeManager $recipeManager,
        ClientManager $clientManager
    )
    {
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);

        if (!$recipe) {
            throw new NotFoundHttpException();
        }

        if (!$clientManager->getUser()) {
            $this->saveTargetPath($session, $firewallMap->getFirewallConfig($request)->getName(), $this->generateUrl('recipe', ['slug' => $slug]));
            return $this->redirectToRoute('app_login');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('toggle-like-token', $submittedToken)) {
            $recipeManager->toggleFavourite($recipe, $clientManager->getUser());
        }

        return $this->json(['hearts' => $recipe->getLikes()]);
    }

}