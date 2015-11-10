<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $manager=$this->getDoctrine()->getManager();
        $query=$manager->getRepository('AppBundle:Anunt')->findAll();

        $paginator  = $this->get('knp_paginator');
        $ads = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10/*limit per page*/
        );
        return [
            'ads'=>$ads
        ];
    }
}
