<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function index(SessionInterface $session)
    {
        $session->remove('Player');
        return $this->redirect($this->generateUrl('login_page'));
    }
}
