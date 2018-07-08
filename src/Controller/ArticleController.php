<?php
namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Pages;
use App\Entity\Posts;
use App\Entity\Quotes;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        /*
        $em = $this->getDoctrine()->getManager();
        //dump($em);

        $em->getConnection()->connect();
        dump($em);
        exit;
        /*
        $connected = $em->getConnection()->isConnected();
        dump($connected);
        exit;
        */

        $stories = $this->getDoctrine()->getRepository(Pages::class)->getStories();
        $story_list=array();
        foreach($stories as $v)
        {
            $diff=date_diff($v->getCreated(),date_create());
            $published='';
            $set=0;//to have max 2 date positions
            if($diff->y>0)
            {
                if($diff->y<2)
                {
                    $published= $diff->y.' years ';
                }
                else
                {
                    $published= $diff->y.' year ';
                }
                $set++;
            }
            if($diff->m>0)
            {
                if($diff->m<2)
                {
                    $published.=$diff->m.' month ';
                }
                else
                {
                    $published.=$diff->m.' months ';
                }
                $set++;
            }
            if($diff->d>0 && $set<2)
            {
                if($diff->d<2)
                {
                    $published.=$diff->d.' day ';
                }
                else
                {
                    $published.=$diff->d.' days ';
                }
                $set++;
            }
            if($diff->h>0 && $set<2)
            {
                if($diff->h<2)
                {
                    $published.=$diff->h.' hour ';
                }
                else
                {
                    $published.=$diff->h.' hours ';
                }
                $set++;
            }
            if($diff->i>0 && $set<2)
            {
                if($diff->i<2)
                {
                    $published.=$diff->i.' minute ';
                }
                else
                {
                    $published.=$diff->i.' minutes ';
                }
                $set++;
            }
            if($published=='')
            {
                $published='few moments';
            }

            $published.='ago';
            //dump($published);
            $story_list[]=array('id'=>$v->getId(), 'name'=>$v->getName(), 'author'=>$v->getAuthor(), 'image'=>'images/'.$v->getImage(),'published'=>$published);
            unset($published);
        }
        unset($stories);
        $quotes= $this->getDoctrine()->getRepository(Quotes::class)->get3Quotes();
        $quote_list=array();
        foreach($quotes as $q)
        {    
            $quote_list[]=array('id'=>$q->getId(),'message'=>$q->getMessage(),'link'=>$q->getLink(),'said_by'=>$q->getSpoke());
        }
        unset($quotes);

        //dump($quote_list);
        //exit;
        return $this->render('article/homepage.html.twig',['story_list'=>$story_list,'quote_list'=>$quote_list]);
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
    public function show($slug, Request $request)
    {

        //dump($slug, $this);
        //dump($request);
        //dump($request->request->get("submitted"));
        //dump($_POST);

        if(isset($_POST['comment_add']))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $product = new Posts();
            $product->setPageId($slug);
            $product->setAuthor('Anonymys');
            $product->setMessage($_POST['comment_add']);

            $entityManager->persist($product);
            $entityManager->flush();
        }
        
        $page_content = $this->getDoctrine()->getRepository(Pages::class)->find($slug);
        if(!$page_content)
        {
            $page_content='';
            //pull 404 page
            //$slug=0;
            $page_content = $this->getDoctrine()->getRepository(Pages::class)->find(0);
        }

        $comments_list = $this->getDoctrine()->getRepository(Posts::class)->findPosts($slug);
        //dump($comments_list);
        if(!$comments_list)
        {
            $comments_list=array();
        }
        else
        {
            $list=array();
            foreach($comments_list as $v)
            {
                $list[]=array('id'=>$v->getId(),'page_id'=>$v->getPageId(),'author'=>$v->getAuthor(),'message'=>$v->getMessage());
            }
            unset($comments_list);
            $comments_list=$list;
            unset($list);
        }


        $diff=date_diff($page_content->getCreated(),date_create());
        $published='';
        $set=0;//to have max 2 date positions
        if($diff->y>0)
        {
            if($diff->y<2)
            {
                $published= $diff->y.' years ';
            }
            else
            {
                $published= $diff->y.' year ';
            }
            $set++;
        }
        if($diff->m>0)
        {
            if($diff->m<2)
            {
                $published.=$diff->m.' month ';
            }
            else
            {
                $published.=$diff->m.' months ';
            }
            $set++;
        }
        if($diff->d>0 && $set<2)
        {
            if($diff->d<2)
            {
                $published.=$diff->d.' day ';
            }
            else
            {
                $published.=$diff->d.' days ';
            }
            $set++;
        }
        if($diff->h>0 && $set<2)
        {
            if($diff->h<2)
            {
                $published.=$diff->h.' hour ';
            }
            else
            {
                $published.=$diff->h.' hours ';
            }
            $set++;
        }
        if($diff->i>0 && $set<2)
        {
            if($diff->i<2)
            {
                $published.=$diff->i.' minute ';
            }
            else
            {
                $published.=$diff->i.' minutes ';
            }
            $set++;
        }
        if($published=='')
        {
            $published='few moments';
        }

        $published.='ago';
        
        return $this->render
        (
            'article/show.html.twig',
            [
                'title'=> "Title",'content'=>'Selected page is: '.$slug,
                'data'=>$slug
                ,'niz'=>array(1,2,3,4,5,6)
                ,'comments'=>array(1,2,3)
                ,'slug'=>$slug
                ,'content'=>array($page_content->getId(),$page_content->getName(),$page_content->getAuthor(),$page_content->getText()
                ,'images/'.$page_content->getImage(),$published)
                ,'comments_list'=>$comments_list
            ]
        );
        /*
        //adding data
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        */
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
