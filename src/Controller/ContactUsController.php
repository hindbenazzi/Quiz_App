<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Reclamation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    /**
     * @Route("/contact_us", name="contact_us")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session,\Swift_Mailer $mailer)
    {
        $repo=$em->getRepository(Player::class);
        $player= $repo->findOneBy(array('UserName'=>$session->get('Player')));
        $rec=new Reclamation();
        $rec->setPlayer($player);
        $form = $this->createFormBuilder($player)
        ->add('Nom', TextType::class)
        ->add('Prenom', TextType::class)
        ->add('Country', TextType::class)
        ->add('Email', TextType::class)
        ->getForm();
        $form1= $this->createFormBuilder($rec)
        ->add('Message', TextareaType::class,array(
            'attr' => array('style' => 'width: 300px; height:100px;')
           ))
        ->getForm();
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $em->persist($rec);
            $em->flush();
            $message = (new \Swift_Message('Hello Email'))
          ->setFrom('hindouxa.hinda@gmail.com')
          ->setTo('hindb788@gmail.com')
          ->setBody( $this->renderView(
            'contact_us/email.txt.twig',
            ['LastName' => $player->getNom(),'FirstName'=>$player->getPrenom(),'Telephone' => $player->getTelephone(),'Email' => $player->getEmail()
            ,'Message' => $rec->getMessage()]
        )
          )
      ;
      $mailer->send($message);
            return $this->redirect($this->generateUrl('home_page'));
            
        }
        return $this->render('contact_us/index.html.twig', [
            'controller_name' => 'ContactUsController','form' => $form->createView(),'form1' => $form1->createView()
        ]);
    }
}
