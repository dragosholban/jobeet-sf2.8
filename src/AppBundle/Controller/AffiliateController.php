<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affiliate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Affiliate controller.
 *
 * @Route("affiliate")
 */
class AffiliateController extends Controller
{
    /**
     * Creates a new affiliate entity.
     *
     * @Route("/new", name="affiliate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affiliate = new Affiliate();
        $form = $this->createForm('AppBundle\Form\AffiliateType', $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affiliate);
            $em->flush();

            return $this->redirectToRoute('affiliate_wait');
        }

        return $this->render('affiliate/new.html.twig', array(
            'affiliate' => $affiliate,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Shows the wait affiliate message.
     *
     * @Route("/wait", name="affiliate_wait")
     * @Method({"GET"})
     */
    public function waitAction()
    {
        return $this->render('affiliate/wait.html.twig');
    }
}
