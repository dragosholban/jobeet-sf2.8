<?php

namespace AppBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Job;
 
class LoadJobData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $job_sensio_labs = new Job();
    $job_sensio_labs->setCategory($em->merge($this->getReference('category-programming')));
    $job_sensio_labs->setType('full-time');
    $job_sensio_labs->setCompany('Sensio Labs');
    $job_sensio_labs->setLogo('sensio-labs.gif');
    $job_sensio_labs->setUrl('http://www.sensiolabs.com/');
    $job_sensio_labs->setPosition('Web Developer');
    $job_sensio_labs->setLocation('Paris, France');
    $job_sensio_labs->setDescription('You\'ve already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.');
    $job_sensio_labs->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
    $job_sensio_labs->setIsPublic(true);
    $job_sensio_labs->setIsActivated(true);
    $job_sensio_labs->setToken('job_sensio_labs');
    $job_sensio_labs->setEmail('job@example.com');
    $job_sensio_labs->setExpiresAt(new \DateTime('2017-10-10'));
    
    $job_extreme_sensio = new Job();
    $job_extreme_sensio->setCategory($em->merge($this->getReference('category-design')));
    $job_extreme_sensio->setType('part-time');
    $job_extreme_sensio->setCompany('Extreme Sensio');
    $job_extreme_sensio->setLogo('extreme-sensio.gif');
    $job_extreme_sensio->setUrl('http://www.extreme-sensio.com/');
    $job_extreme_sensio->setPosition('Web Designer');
    $job_extreme_sensio->setLocation('Paris, France');
    $job_extreme_sensio->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.');
    $job_extreme_sensio->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
    $job_extreme_sensio->setIsPublic(true);
    $job_extreme_sensio->setIsActivated(true);
    $job_extreme_sensio->setToken('job_extreme_sensio');
    $job_extreme_sensio->setEmail('job@example.com');
    $job_extreme_sensio->setExpiresAt(new \DateTime('2017-10-10'));
 
    $em->persist($job_sensio_labs);
    $em->persist($job_extreme_sensio);
 
    $em->flush();
  }
 
  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
}