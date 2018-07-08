<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomExceptionsController extends Controller
{
    /**
     * @Route("/custom/exceptions", name="custom_exceptions")
     */
    public function index($exception, $logger)
    {
        return $this->render('custom_exceptions/index.html.twig', [
            'controller_name' => 'CustomExceptionsController','exception'=>$exception, 'log'=>$logger
        ]);
    }
}
