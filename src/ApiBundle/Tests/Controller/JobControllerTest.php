<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 
class JobControllerTest extends WebTestCase
{
    public function testGetJobs()
    {
        $client = static::createClient();

        // A token is needed to access the service
        $crawler = $client->request('GET', '/api/jobs.json');
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        
        // An inactive account cannot access the web service
        $crawler = $client->request('GET', '/api/jobs/symfony.json');
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());

        // The web service supports the JSON format
        $crawler = $client->request('GET', '/api/jobs/sensio_labs.json');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue('application/json' == $client->getResponse()->headers->get('content-type'));
        $jobs = json_decode($client->getResponse()->getContent());
        $this->assertTrue(null !== $jobs);
        
        // The jobs returned are limited to the categories configured for the affiliate
        foreach($jobs as $job) {
            $this->assertTrue('Programming' == $job->category_name);
        }
    }
}