<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 
class JobControllerTest extends WebTestCase
{
    public function getMostRecentProgrammingJob()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT j from AppBundle:Job j LEFT JOIN j.category c WHERE c.slug = :slug AND j.expiresAt > :date ORDER BY j.createdAt DESC');
        $query->setParameter('slug', 'programming');
        $query->setParameter('date', date('Y-m-d H:i:s', time()));
        $query->setMaxResults(1);
        
        return $query->getSingleResult();
    }

    public function getExpiredJob()
    {
      $kernel = static::createKernel();
      $kernel->boot();
      $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

      $query = $em->createQuery('SELECT j from AppBundle:Job j WHERE j.expiresAt < :date');     $query->setParameter('date', date('Y-m-d H:i:s', time()));
      $query->setMaxResults(1);
      return $query->getSingleResult();
    }

    public function testIndex()
    {
      // get the custom parameters from app config.yml
      $kernel = static::createKernel();
      $kernel->boot();
      $max_jobs_on_homepage = $kernel->getContainer()->getParameter('max_jobs_on_homepage');
      $max_jobs_on_category = $kernel->getContainer()->getParameter('max_jobs_on_category');

      $client = static::createClient();

      $crawler = $client->request('GET', '/');
      $this->assertEquals('AppBundle\Controller\JobController::indexAction', $client->getRequest()->attributes->get('_controller'));

      // expired jobs are not listed
      $this->assertTrue($crawler->filter('.jobs td.position:contains("Expired")')->count() == 0);

      // only $max_jobs_on_homepage jobs are listed for a category
      $this->assertTrue($crawler->filter('.category_programming tr')->count() <= $max_jobs_on_homepage);
      $this->assertTrue($crawler->filter('.category_design .more_jobs')->count() == 0);
      $this->assertTrue($crawler->filter('.category_programming .more_jobs')->count() == 1);

      // jobs are sorted by date
      $this->assertTrue($crawler->filter('.category_programming tr')->first()->filter(sprintf('a[href*="/%d/"]', $this->getMostRecentProgrammingJob()->getId()))->count() == 1);

      // each job on the homepage is clickable and give detailed information
      $job = $this->getMostRecentProgrammingJob();
      $link = $crawler->selectLink('Web Developer')->first()->link();
      $crawler = $client->click($link);
      $this->assertEquals('AppBundle\Controller\JobController::showAction', $client->getRequest()->attributes->get('_controller'));
      $this->assertEquals($job->getCompanySlug(), $client->getRequest()->attributes->get('company'));
      $this->assertEquals($job->getLocationSlug(), $client->getRequest()->attributes->get('location'));
      $this->assertEquals($job->getPositionSlug(), $client->getRequest()->attributes->get('position'));
      $this->assertEquals($job->getId(), $client->getRequest()->attributes->get('id'));

      // a non-existent job forwards the user to a 404
      $crawler = $client->request('GET', '/job/foo-inc/milano-italy/0/painter');
      $this->assertTrue(404 === $client->getResponse()->getStatusCode());

      // an expired job page forwards the user to a 404
      $crawler = $client->request('GET', sprintf('/job/sensio-labs/paris-france/%d/web-developer', $this->getExpiredJob()->getId()));
      $this->assertTrue(404 === $client->getResponse()->getStatusCode());
    }
}