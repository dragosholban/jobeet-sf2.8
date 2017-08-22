<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class JobController extends FOSRestController
{
    /**
     * @Rest\View
     */
    public function getJobsAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        
        $affiliate = $em->getRepository('AppBundle:Affiliate')->findOneByToken($token);
        if(!$affiliate || !$affiliate->getIsActive()) {
            throw $this->createNotFoundException();
        }
        
        $jobs = $em->getRepository('AppBundle:Job')->getActiveJobsForAffiliate($affiliate);
        $view = $this->view($jobs);
        
        return $this->handleView($view);
    }
}
