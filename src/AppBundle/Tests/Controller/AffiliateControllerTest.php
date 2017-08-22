<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AffiliateControllerTest extends WebTestCase
{
    public function getProgrammingCategory()
    {
      $kernel = static::createKernel();
      $kernel->boot();
      $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

      $query = $em->createQuery('SELECT c from AppBundle:Category c WHERE c.slug = :slug');
      $query->setParameter('slug', 'programming');
      $query->setMaxResults(1);
      
      return $query->getSingleResult();
    }
    
    public function testAffiliateForm()
    {
        $client = static::createClient();
        
        // An affiliate can create an account
        $crawler = $client->request('GET', '/affiliate/new');
        $this->assertEquals('AppBundle\Controller\AffiliateController::newAction', $client->getRequest()->attributes->get('_controller'));
        
        $form = $crawler->selectButton('Submit')->form(array(
            'appbundle_affiliate[url]'           => 'http://www.example.com/',
            'appbundle_affiliate[email]'         => 'foo@example.com',
            'appbundle_affiliate[categories][1]' => $this->getProgrammingCategory()->getId(),
        ));
        
        $crawler = $client->submit($form);
        $this->assertEquals('AppBundle\Controller\AffiliateController::newAction', $client->getRequest()->attributes->get('_controller'));
        $crawler = $client->followRedirect();
        $this->assertEquals('AppBundle\Controller\AffiliateController::waitAction', $client->getRequest()->attributes->get('_controller'));

        $this->assertTrue($crawler->filter('#content h1')->count() == 1);
        $this->assertTrue($crawler->filter('#content h1')->text() == 'Your affiliate account has been created');
        
        // An affiliate must at least select one category
        $crawler = $client->request('GET', '/affiliate/new');
        
        $form = $crawler->selectButton('Submit')->form(array(
            'appbundle_affiliate[url]'        => 'http://www.example.com/',
            'appbundle_affiliate[email]'      => 'foo@example.com',
        ));
        
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('#appbundle_affiliate_categories')->siblings()->first()->filter('.error_list')->count() == 1);
    }
}
