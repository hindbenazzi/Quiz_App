<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class QuizesController extends AbstractController
{
    /**
     * @Route("/subjects", name="subjects")
     */
    public function index(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $repo=$em->getRepository(Subject::class);
        $Subjects=$repo->findAll();
        return $this->render('quizes/index.html.twig', [
            'controller_name' => 'QuizesController','Subjects'=>$Subjects
        ]);
    }

     /**
     * @Route("/CreateSubject", name="CreateSubject")
     */
    public function Create(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $sub=new Subject();
        $form = $this->createFormBuilder($sub)
                     ->add('Title', TextType::class,['attr'=>['placeholder' => 'Enter a title for your subject']])
                     ->add('Description', TextareaType::class,['attr'=>['placeholder' => 'Enter a description for your subject','row'=>'10']])
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($sub);
        $em->flush();
        return $this->redirect($this->generateUrl('subjects'));
        }
        return $this->render('quizes/CreateSub.html.twig', ['form' => $form->createView()]);
    }
}
