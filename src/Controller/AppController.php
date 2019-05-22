<?php


namespace App\Controller;


use App\Form\RecipeSearchType;
use App\Service\FlashManager;
use App\Service\RecipeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function homepage(Request $request, RecipeManager $recipeManager, FlashManager $flashManager)
    {
        $form = $this->createForm(RecipeSearchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($form['phrase']) && empty($form['ingredients'])) {
                $this->addFlash(FlashManager::FLASH_TYPE_WARNING, FlashManager::FLASH_MESSAGE_SEARCH_EMPTY);

                return $this->redirect($request->getUri());
            }

            dump($form->getData());

            return $this->redirectToRoute('search', ['page' => 1, 'phrase' => $form['phrase']->getData(), 'ingredients' => implode(',', $form['ingredients']->getData())]);

        }
        return $this->render('homepage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}