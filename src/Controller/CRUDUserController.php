<?php


namespace App\Controller;


use App\Form\PasswordChangeType;
use App\Service\ClientManager;
use App\Service\FlashManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;


class CRUDUserController extends CRUDController
{

    /**
     * @param $id
     */
    public function changePasswordAction($id, Request $request, TranslatorInterface $translator, ClientManager $clientManager)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        $form = $this->createForm(PasswordChangeType::class, $object, ['action' => $this->admin->generateObjectUrl('changePassword', $object)]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientManager->saveUserPassword($form->getData(), $form['plainPassword']->getData());

            $this->addFlash('sonata_flash_success', $translator->trans(FlashManager::FLASH_MESSAGE_FORM_DATA_SAVED));

            if (null !== $request->get('btn_update_and_list')) {
                return $this->redirectToList();
            }
        }

        return $this->renderWithExtraParams('admin/changePassword.html.twig', [
            'form' => $form->createView(),
            'object' => $object,
            'action' => 'changePassword',
        ]);

    }


}