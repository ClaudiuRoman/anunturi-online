<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Anunt;
use AppBundle\Form\AnuntType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnuntController extends Controller{
    /**
     * @Route("/newad/", name="new-ad")
     * @Template()
     */
    public function newAdAction(Request $request){

        $form=$this->createForm(new AnuntType());

        if ($form->handleRequest($request) && $form->isValid()) {
            $ad=$form->getData();
            $ad->setIsPublished(TRUE);
            $ad->setCreatedAt(new \DateTime('now'));
            $user=$this->getUser();
            $ad->setUser($user);
//            var_dump($ad);
//            die();
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();
            return $this->redirectToRoute('homepage');
        }

        return [
            'form'=>$form->createView()
        ];
//        return $this->render('@App/Default/newad.html.twig',
//            array('form'=>$form)
//        );
    }
}