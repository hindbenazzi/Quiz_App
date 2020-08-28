<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MyprofileController extends AbstractController
{
    /**
     * @Route("/myprofile", name="myprofile")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
 
        $repo=$em->getRepository(Player::class);
        $player= $repo->findOneBy(array('UserName'=>$session->get('Player')));
        $form = $this->createFormBuilder($player)
                     ->add('UserName', TextType::class)
                     ->add('Password', TextType::class)
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
        }
        return $this->render('myprofile/index.html.twig', [
            'controller_name' => 'MyprofileController','form' => $form->createView()
        ]);
    }
}
