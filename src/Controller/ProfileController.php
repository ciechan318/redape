<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/data", name="profileData")
     */
    public function profileData()
    {
        return $this->render('profile/data.html.twig', [
        ]);
    }
}
