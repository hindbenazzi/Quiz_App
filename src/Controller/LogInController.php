<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LogInController extends AbstractController
{
    /**
     * @Route("/", name="login_page")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $player=new Player();
        $form = $this->createFormBuilder($player)
                     ->add('UserName', TextType::class,['attr'=>['placeholder' => 'Your UserName']])
                     ->add('Password', PasswordType::class,['attr'=>['placeholder' => 'Your Password']])
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo=$em->getRepository(Player::class);
            $player1= $repo->findOneBy(array('UserName'=>$player->getUserName(),'Password'=>$player->getPassword()));
            if($player1==null){
                
                return $this->render('login/login.html.twig',['form' => $form->createView()]);
            }else{
            $session->set('Player',$player1->getUserName());
            return $this->redirect($this->generateUrl('home_page'));
        }
        }
        return $this->render('login/login.html.twig',['form' => $form->createView()]);
    }
    /**
     * @Route("/redirect", name="Tologin_page")
     */
    public function Redirection(EntityManagerInterface $em,Request $request)
    {
        return $this->redirect("/");
    }
}
