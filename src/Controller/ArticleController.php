<?php
namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }
    /**
     * @Route("/news/")
     */
    public function show_basic()
    {
        //dump($slug, $this);
        return $this->render
        (
            'article/show.html.twig',['title'=> "Title",'content'=>'Selected base news page',
            'data'=>0
            ,'niz'=>array(1,2,3,4,5,6)
            ,'comments'=>array(1,2,3)]
        );
    }
    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug)
    {
        //dump($slug, $this);
        return $this->render
        (
            'article/show.html.twig',['title'=> "Title",'content'=>'Selected page is: '.$slug,
            'data'=>$slug
            ,'niz'=>array(1,2,3,4,5,6)
            ,'comments'=>array(1,2,3)
            ,'slug'=>$slug]
        );
    }

    /**
    * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"});
    *
    */
    public function toogleArticleHeart($slug)
    {
        //DB missing
        //return new Response(json_encode(['hearts'=>5]));
        return new JsonResponse(['hearts'=>rand(5,10)]);
        return $this->json(['hearts'=>rand(5,10)]);
    }
}
