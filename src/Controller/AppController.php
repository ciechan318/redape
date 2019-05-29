<?php


namespace App\Controller;


use App\Form\RecipeSearchType;
use App\Service\FlashManager;
use App\Service\LanguageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function homepage(Request $request)
    {
        $form = $this->createForm(RecipeSearchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($form['phrase']) && empty($form['ingredients'])) {
                $this->addFlash(FlashManager::FLASH_TYPE_WARNING, FlashManager::FLASH_MESSAGE_SEARCH_EMPTY);

                return $this->redirect($request->getUri());
            }

            $page = 1;
            $phrase = $form['phrase']->getData();
            $ingredients = implode(',', $form['ingredients']->getData());

            if (!empty($phrase) && !empty($ingredients)) {
                return $this->redirectToRoute('searchPhraseIngredients', ['page' => $page, 'phrase' => $phrase, 'ingredients' => $ingredients]);
            }

            if (!empty($phrase)) {
                return $this->redirectToRoute('searchPhrase', ['page' => $page, 'phrase' => $phrase]);
            }
            if (!empty($ingredients)) {
                return $this->redirectToRoute('searchIngredients', ['page' => $page, 'ingredients' => $ingredients]);
            }
        }

        return $this->render('homepage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="changeLocale")
     * @return Response
     */
    public function changeLocale(string $locale, Request $request, LanguageManager $languageManager)
    {
        if (!in_array($locale, $languageManager->getAppLocales())) {
            throw new NotFoundHttpException();
        }

        $request->setLocale($locale);

        return $this->redirect($request->headers->get('referer'));
    }

}