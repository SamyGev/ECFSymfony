<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Grade;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use App\Form\GradeType;

class PieceController extends AbstractController
{

    /**
     * @Route("/piece", name="piece")
     */
    public function index( ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('piece/index.html.twig', [
            'controller_name' => 'PieceController', 
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('piece/home.html.twig');
    }

    /**
     *@Route("/piece/new", name="piece_new")
     *@Route("/piece/{id}/edit", name="piece_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager){
        if (!$article){
            $article = new Article();
        }
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(( ($article->getPrice()*1000)%10 ) !== 0 ){
                $article->setPrice(floor($article->getPrice()*100)/100);
            }
            $article->setImageAlt("L'image de " . $article->getName() . " n'a pas été trouvé");
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('piece_show',[
                'id' => $article->getId()
            ]);
        }
        return $this->render('piece/create.html.twig',[
            'editMode' => $article->getId() !== null,
            'formArticle' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     *@Route("/piece/{id}", name="piece_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager){
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($form->isSubmitted() && $form->isValid()){
            if($user->getId() === null){
                $grade->setAuthor("Anonyme");
            }
            else{
                $grade->setAuthor($user->getUserName());
            }
            $grade->setCreatedAt(new \DateTime())
                  ->setArticle($article);
            $manager->persist($grade);
            $manager->flush();
        }
    return $this->render('piece/show.html.twig',[
        'article' => $article,
        'gradeForm' => $form->createView(),
        'user' => $user
    ]);
    }

    /**
     *@Route("/piece/{id}/delete", name="piece_delete")
     */
    public function delete(Article $article){
        return $this->render('piece/delete.html.twig', [
            'article' => $article
        ]);
    }
    /**
     *@Route("/piece/{id}/deleteConfirm", name="piece_delete_confirm")
     */
    public function deleteConfirm(Article $article, EntityManagerInterface $manager){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if( $user == "anon."){
            return $this->redirectToRoute('home');
        }else{
            $manager->remove($article);
            $manager->flush();
            return $this->render('piece/deleteConfirm.html.twig', [
                'article' => $article
            ]);
        }
        
    }
    
}
