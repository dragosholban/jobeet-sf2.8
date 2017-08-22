<?php

namespace AppBundle\Controller;
 
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
 
class AffiliateAdminController extends Controller
{
    public function activateAction($id)
    {
        if($this->admin->isGranted('EDIT') === false) {
            throw new AccessDeniedException();
        }
 
        $em = $this->getDoctrine()->getManager();
        $affiliate = $em->getRepository('AppBundle:Affiliate')->findOneById($id);
 
        try {
            $affiliate->setIsActive(true);
            $em->flush();
            
            $message = (new \Swift_Message('Jobeet affiliate token'))
                ->setFrom('jobeet@example.com')
                ->setTo($affiliate->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        array('affiliate' => $affiliate)
                    ),
                    'text/html'
                )
            ;

            $this->get('mailer')->send($message);
            
        } catch(Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());
 
            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }
 
        return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
    }
    
    public function deactivateAction($id)
    {
        if($this->admin->isGranted('EDIT') === false) {
            throw new AccessDeniedException();
        }
 
        $em = $this->getDoctrine()->getManager();
        $affiliate = $em->getRepository('AppBundle:Affiliate')->findOneById($id);
 
        try {
            $affiliate->setIsActive(false);
            $em->flush();
        } catch(Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());
 
            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }
 
        return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
    }
}