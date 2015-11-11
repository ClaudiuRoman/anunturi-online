<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Anunt;
use AppBundle\Form\AnuntType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AnuntController
 * @package AppBundle\Controller
 * @Route("/anunturi")
 */
class AnuntController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $repository=$this->getDoctrine()->getRepository('AppBundle:Anunt');

        $repository->findAnunturi($request->get('search'));

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

    /**
     * @Route("/new", name="new-ad")
     * @Template()
     */
    public function newAdAction(Request $request)
    {
        $anunt = new Anunt($this->getUser());
        $form = $this->createForm(new AnuntType(), $anunt);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($anunt);
            $manager->flush();

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/edit/{id}", name="edit_article")
     * @Template()
     */
    public function editAdAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('AppBundle:Anunt')->find($id);
        if (!$ad) {
            throw $this->createNotFoundException(
                'No ad found for id ' . $id
            );
        }

        if ($ad->getUser() != $this->getUser()) {
            throw $this->createNotFoundException('You don\'t have rights to edit this ad! ');
        }

        $form = $this->createForm(new AnuntType(), $ad);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Anunt:editAd.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_anunt")
     */
    public function deleteAdAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('AppBundle:Anunt')->find($id);
        if (!$ad) {
            throw $this->createNotFoundException(
                'No ad found for id ' . $id
            );
        }
        if ($ad->getUser() != $this->getUser()) {
            throw $this->createNotFoundException(
                'You don\'t have rights to delete this ad!'
            );
        }

        $em->remove($ad);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/view/{id}",name="view_ad")
     * @Template
     */
    public function viewAdAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('AppBundle:Anunt')->find($id);
        if (!$ad) {
            throw $this->createNotFoundException(
                'No ad found for id ' . $id
            );
        }

        return $this->render('@App/Anunt/viewAd.html.twig', array(
                'ad' => $ad)
        );
    }
}