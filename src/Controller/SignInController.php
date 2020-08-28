<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends AbstractController
{
    /**
     * @Route("/sign/in", name="signin")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {

        $player=new Player();
        $form = $this->createFormBuilder($player)
                     ->add('UserName', TextType::class)
                     ->add('Password', PasswordType::class)
                     ->add('Nom', TextType::class)
                     ->add('Prenom', TextType::class)
                     ->add('DateNaissance', DateType::class,['widget' => 'single_text'])
                     ->add('Country', TextType::class)
                     ->add('Email', TextType::class)
                     ->add('Telephone', TextType::class)
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($player);
            $em->flush();
            $session->set('Player',$player->getUserName());
            return $this->redirect($this->generateUrl('home_page'));
        }
        return $this->render('sign_in/index.html.twig', [
            'controller_name' => 'SignInController','form' => $form->createView()
        ]);
    }
}
