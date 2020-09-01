<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                     ->add('Description', TextareaType::class,['attr'=>['placeholder' => 'Enter a description for your subject','rows'=>'8']])
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($sub);
        $em->flush();
        return $this->redirect($this->generateUrl('subjects'));
        }
        return $this->render('quizes/CreateSub.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/Quizzes/{id}", name="ShowQuizzes")
     */
    public function ShowQuizzes($id,EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $repo1=$em->getRepository(Subject::class);
        $subject=$repo1->findOneBy(array('id'=>$id));
        $repo=$em->getRepository(Quiz::class);
        $Quizzes=$repo->findBy(array('Subject'=>$subject));
        
        return $this->render('quizes/Quizzes.html.twig',['Quizzes'=>$Quizzes,'Subject'=>$subject]);
    }
    /**
     * @Route("/CreateQuiz/{id}", name="CreateQuiz")
     */
    public function CreateQuiz($id,EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $repo3=$em->getRepository(Player::class);
        $player= $repo3->findOneBy(array('UserName'=>$session->get('Player')));
        $repo1=$em->getRepository(Subject::class);
        $subject=$repo1->findOneBy(array('id'=>$id));
        $quiz=new Quiz();
        $form = $this->createFormBuilder($quiz)
                     ->add('Title', TextType::class,['attr'=>['placeholder' => 'Enter a title for your quiz']])
                     ->add('Description', TextareaType::class,['attr'=>['placeholder' => 'Enter a description for your quiz','rows'=>'5']])
                     ->add('NbrQst', IntegerType::class,['attr'=>['placeholder' => 'Number of questions']])
                     ->add('difficulty', IntegerType::class,['attr'=>['placeholder' => 'level of difficulty']])
                     ->add('ImageUrlFile',VichImageType::class)
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $quiz->setSubject($subject);
        $quiz->setCreatedBy($player);
        $em->persist($quiz);
        $em->flush();
        return $this->redirect($this->generateUrl('AddQuestions',['id' => $quiz->getId()]));
        }
        return $this->render('quizes/Createquiz.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/AddQuestions/{id}", name="AddQuestions")
     */
    public function AddQuestions($id,EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $repo1=$em->getRepository(Quiz::class);
        $quiz=$repo1->findOneBy(array('id'=>$id));
        $question=new Question();
        $form = $this->createFormBuilder($question)
                     ->add('Contenu', TextareaType::class,['attr'=>['placeholder' => 'Enter the question here','rows'=>'5']])
                     ->add('Ans1', TextareaType::class,['attr'=>['placeholder' => 'First option','rows'=>'5']])
                     ->add('Ans2', TextareaType::class,['attr'=>['placeholder' => 'Second option','rows'=>'5']])
                     ->add('Ans3', TextareaType::class,['attr'=>['placeholder' => 'Third option','rows'=>'5']])
                     ->add('Ans4',TextareaType::class,['attr'=>['placeholder' => 'Fourth option','rows'=>'5']])
                     ->add('CorrectAns', IntegerType::class,['attr'=>['placeholder' => 'the number of the correct option']])
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $question->setQuiz($quiz);
        $em->persist($question);
        $em->flush();
        return $this->redirect($this->generateUrl('AddQuestions',['id' => $id]));
        }
        return $this->render('quizes/AddQuestions.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/LoadQuiz/{id}", name="LoadQuiz")
     */
     public function LoadQuiz($id,EntityManagerInterface $em,Request $request,SessionInterface $session)
     {
        $repo3=$em->getRepository(Quiz::class);
        $quiz= $repo3->findOneBy(array('id'=>$id));
        $repo=$em->getRepository(Question::class);
        $Questions=$repo->findBy(array('Quiz'=>$quiz));
        $Qsts=array();
        foreach($Questions as $key => $value){
            $Qsts[$key]=$value;
        }
        $session->set('NbrQst',count($Qsts));
        $session->set('QstId',0);
        return $this->redirect($this->generateUrl('StartQuiz',['id' => $id]));
     }
    /**
     * @Route("/StartQuiz/{id}", name="StartQuiz")
     */
    public function StartQuiz($id,EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
    
        $repo3=$em->getRepository(Quiz::class);
        $quiz= $repo3->findOneBy(array('id'=>$id));
        $repo=$em->getRepository(Question::class);
        $Questions=$repo->findBy(array('Quiz'=>$quiz));
        $Qsts=array();
        foreach($Questions as $key => $value){
            $Qsts[$key]=$value;
        }
        
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
            $jsonData = array();  
            $idx = 0;  
            $session->set('QstId',(int)$session->get('QstId')+1);
            if($session->get('QstId')<$session->get('NbrQst')){
               $temp = array(
                  'Contenu' => $Questions[$session->get('QstId')]->getContenu(),  
                  'Ans1' => $Questions[$session->get('QstId')]->getAns1(),
                  'Ans2' => $Questions[$session->get('QstId')]->getAns1(), 
                  'Ans3' => $Questions[$session->get('QstId')]->getAns1(), 
                  'Ans4' => $Questions[$session->get('QstId')]->getAns1()  
               );
               $jsonData[$idx++] = $temp;  
            }else{
                $jsonData[$idx++] ="finished"; 
            } 
            return new JsonResponse($jsonData); 
         }  else {  
         // Normal request  
         return $this->render('quizes/StartQuiz.html.twig',['Questions'=>$Questions]);
        }
        
        return $this->render('quizes/StartQuiz.html.twig',['Questions'=>$Questions,'nbrQst'=>$session->get('QstId')]);
    }
    /**
     * @Route("/MYQuizzes", name="MyQuizzes")
     */
    public function ShowMyQuizzes(EntityManagerInterface $em,Request $request,SessionInterface $session)
    {
        $repo3=$em->getRepository(Player::class);
        $player= $repo3->findOneBy(array('UserName'=>$session->get('Player')));
        $repo=$em->getRepository(Quiz::class);
        $Quizzes=$repo->findBy(array('CreatedBy'=>$player));
        
        return $this->render('quizes/MyQuizzes.html.twig',['Quizzes'=>$Quizzes]);
    }
}
