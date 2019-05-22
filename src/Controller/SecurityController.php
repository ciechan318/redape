<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Form\UserRegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Service\ClientManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class);
        // last username entered by the user
        $form['email']->setData($authenticationUtils->getLastUsername());

        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            // get the login error if there is one
            $form->addError(new FormError($error->getMessage()));
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {
        // may be empty
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, FirewallMap $firewallMap, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, ClientManager $clientManager)
    {
        $form = $this->createForm(UserRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $clientManager->saveUserPassword($user, $form['plainPassword']->getData());

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                $firewallMap->getFirewallConfig($request)->getName()
            );
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
