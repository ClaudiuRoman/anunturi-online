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
        $repository=$this->getDoctrine()->getRepository('AppBundle:Anunt');

        $results = $repository->findAnunturi($request->get('search'));

        $paginator  = $this->get('knp_paginator');
        $ads = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10/*limit per page*/
        );

        return [
            'ads'=>$ads
        ];
    }
}
