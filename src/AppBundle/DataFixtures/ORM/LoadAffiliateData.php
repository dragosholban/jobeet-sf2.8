<?php

namespace AppBundle\DataFixtures\ORM;
 
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Affiliate;
 
class LoadAffiliateData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $affiliate = new Affiliate();
        $affiliate->setUrl('http://www.sensio-labs.com/');
        $affiliate->setEmail('fabien.potencier@example.com');
        $affiliate->setIsActive(true);
        $affiliate->setToken('sensio_labs');
        $affiliate->addCategory($this->getReference('category-programming'));
        $em->persist($affiliate);

        $affiliate = new Affiliate();
        $affiliate->setUrl('/');
        $affiliate->setEmail('fabien.potencier@example.com');
        $affiliate->setIsActive(false);
        $affiliate->setToken('symfony');
        $affiliate->addCategory($this->getReference('category-design'));
        $affiliate->addCategory($this->getReference('category-programming'));
        $em->persist($affiliate);
        
        $em->flush();
    }
 
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}