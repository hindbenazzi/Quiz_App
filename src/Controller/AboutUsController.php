<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    /**
     * @Route("/about_us", name="about_us")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        
        $repo=$em->getRepository(Player::class);
        $repo1=$em->getRepository(Rating::class);
        $player= $repo->findOneBy(array('UserName'=>$session->get('Player')));
        $rate=$repo1->findOneBy(array('Player'=>$player));
        if($rate==null){
            $rate=new Rating();
        }
        $rate->setPlayer($player);
        $form = $this->createFormBuilder($rate)
                     ->add('Rating', HiddenType::class,[
                        'required'   => false,
                        'attr' => ['class' => 'rate','Id'=>'rate'],
                    ])
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rate);
            $em->flush();
            return $this->redirect($this->generateUrl('home_page'));
            
        }
        return $this->render('about_us/index.html.twig', [
            'controller_name' => 'AboutUsController','form' => $form->createView()
        ]);
    }
}
