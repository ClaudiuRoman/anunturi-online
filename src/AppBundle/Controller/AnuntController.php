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
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }

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
    }

    /**
     * @Route("/editad/{id}", name="")
     * @Template()
     */
    public function editAdAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('AppBundle:Anunt')->find($id);
        if (!$ad) {
            throw $this->createNotFoundException(
                'No ad found for id ' . $id
            );
        }
        $currentUser=$this->getUser();
        if($ad->getUser()!=$currentUser){
            throw $this->createNotFoundException(
                'You don\'t have rights to edit this ad! ');
        }

        $form = $this->createForm(new AnuntType(),$ad);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return new Response('Ad updated successfully');
        }

        $build['form'] = $form->createView();

        return $this->render('AppBundle:Anunt:editAd.html.twig', $build);
    }
}