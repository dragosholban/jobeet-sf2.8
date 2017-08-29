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
    
    public function testJobForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/job/new');
        $this->assertEquals('AppBundle\Controller\JobController::newAction', $client->getRequest()->attributes->get('_controller'));

        $form = $crawler->selectButton('Preview your job')->form(array(
            'job[company]'      => 'Sensio Labs',
            'job[url]'          => 'http://www.sensio.com/',
            'job[file]'         => __DIR__.'/../../../../../web/bundles/app/images/sensio-labs.gif',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[description]'  => 'You will work with symfony to develop websites for our customers.',
            'job[howToApply]' => 'Send me an email',
            'job[email]'        => 'for.a.job@example.com',
            'job[isPublic]'    => false,
        ));

        $client->submit($form);
        $this->assertEquals('AppBundle\Controller\JobController::newAction', $client->getRequest()->attributes->get('_controller'));
        $client->followRedirect();
        $this->assertEquals('AppBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));
        
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from AppBundle:Job j WHERE j.location = :location AND j.isActivated IS NULL AND j.isPublic = 0');
        $query->setParameter('location', 'Atlanta, USA');
        $this->assertTrue(0 < $query->getSingleScalarResult());
        
        $crawler = $client->request('GET', '/job/new');
        $form = $crawler->selectButton('Preview your job')->form(array(
          'job[company]'      => 'Sensio Labs',
          'job[position]'     => 'Developer',
          'job[location]'     => 'Atlanta, USA',
          'job[email]'        => 'not.an.email',
        ));
        $crawler = $client->submit($form);

        // check if we have 3 errors
        $this->assertTrue($crawler->filter('.error_list')->count() == 3);
        // check if we have error on job_description field
        $this->assertTrue($crawler->filter('#job_description')->siblings()->first()->filter('.error_list')->count() == 1);
        // check if we have error on job_how_to_apply field
        $this->assertTrue($crawler->filter('#job_howToApply')->siblings()->first()->filter('.error_list')->count() == 1);
        // check if we have error on job_email field
        $this->assertTrue($crawler->filter('#job_email')->siblings()->first()->filter('.error_list')->count() == 1);
    }
    
    public function createJob($values = array(), $publish = false)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/job/new');
        $form = $crawler->selectButton('Preview your job')->form(array_merge(array(
            'job[company]'      => 'Sensio Labs',
            'job[url]'          => 'http://www.sensio.com/',
            'job[position]'     => 'Developer',
            'job[location]'     => 'Atlanta, USA',
            'job[description]'  => 'You will work with symfony to develop websites for our customers.',
            'job[howToApply]'   => 'Send me an email',
            'job[email]'        => 'for.a.job@example.com',
            'job[isPublic]'     => false,
        ), $values));

        $client->submit($form);
        $client->followRedirect();
        
        if($publish) {
            $crawler = $client->getCrawler();
            $form = $crawler->selectButton('Publish')->form();
            $client->submit($form);
            $client->followRedirect();
        }

        return $client;
    }
    
    public function getJobByPosition($position)
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT j from AppBundle:Job j WHERE j.position = :position');
        $query->setParameter('position', $position);
        $query->setMaxResults(1);
        
        return $query->getSingleResult();
    }
    
    public function testPublishJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO1'));
        $crawler = $client->getCrawler();
        $form = $crawler->selectButton('Publish')->form();
        $client->submit($form);

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from AppBundle:Job j WHERE j.position = :position AND j.isActivated = 1');
        $query->setParameter('position', 'FOO1');
        $this->assertTrue(0 < $query->getSingleScalarResult());
    }
    
    public function testDeleteJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO2'));
        $crawler = $client->getCrawler();
        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery('SELECT count(j.id) from AppBundle:Job j WHERE j.position = :position');
        $query->setParameter('position', 'FOO2');
        $this->assertTrue(0 == $query->getSingleScalarResult());
    }
    
    public function testEditJob()
    {
        $client = $this->createJob(array('job[position]' => 'FOO3'), true);
        $crawler = $client->getCrawler();
        $crawler = $client->request('GET', sprintf('/job/%s/edit', $this->getJobByPosition('FOO3')->getToken()));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
    }
    
    public function testExtendJob()
    {
        // A job validity cannot be extended before the job expires soon
        $client = $this->createJob(array('job[position]' => 'FOO4'), true);
        $crawler = $client->getCrawler();
        $this->assertTrue($crawler->filter('input[type=submit]:contains("Extend")')->count() == 0);

        // A job validity can be extended when the job expires soon

        // Create a new FOO5 job
        $client = $this->createJob(array('job[position]' => 'FOO5'), true);
        // Get the job and change the expire date to today
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $job = $em->getRepository('AppBundle:Job')->findOneByPosition('FOO5');
        $job->setExpiresAt(new \DateTime());
        $em->flush();
        // Go to the preview page and extend the job
        $crawler = $client->request('GET', sprintf('/job/%s/%s/%s/%s', $job->getCompanySlug(), $job->getLocationSlug(), $job->getToken(), $job->getPositionSlug()));
        $crawler = $client->getCrawler();
        $form = $crawler->selectButton('Extend')->form();
        $client->submit($form);
        // Reload the job from db
        $job = $this->getJobByPosition('FOO5');
        // Check the expiration date
        $this->assertTrue($job->getExpiresAt()->format('y/m/d') == date('y/m/d', time() + 86400 * 30));
    }
    
    public function testSearch() 
    {
        // Non activated jobs are not returned by the search
        $client = $this->createJob(array('job[position]' => 'foobar1'), false);
        $crawler = $client->request('GET', '/search?query=foobar1');
        $this->assertEquals('AppBundle\Controller\JobController::searchAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue($crawler->filter('.jobs td.position:contains("foobar1")')->count() == 0);
        
        // Activated jobs are returned by the search
        $client = $this->createJob(array('job[position]' => 'foobar2'), true);
        $crawler = $client->request('GET', '/search?query=foobar2');
        $this->assertTrue($crawler->filter('.jobs td.position:contains("foobar2")')->count() == 1);
        
        // Deleted jobs are not returned by the search
        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT j from AppBundle:Job j WHERE j.position = :position');
        $query->setParameter('position', 'foobar2');
        $query->setMaxResults(1);
        $job = $query->getSingleResult();
        $em->remove($job);
        $em->flush();
        $crawler = $client->request('GET', '/search?query=foobar2');
        $this->assertTrue($crawler->filter('.jobs td.position:contains("foobar2")')->count() == 0);        
    }
}