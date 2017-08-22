<?php

namespace AppBundle\Admin;
 
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
 
class AffiliateAdmin extends AbstractAdmin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'is_active',
        'isActive' => array('value' => 2) // The value 2 represents that the displayed affiliate accounts are not activated yet
    );
     
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('isActive')
            ->add('email')
            ->add('url')
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('url')
            ->add('isActive')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'activate' => array('template' => 'affiliateAdmin/list_action_activate.html.twig'),
                    'deactivate' => array('template' => 'affiliateAdmin/list_action_deactivate.html.twig'),
                )
            ))
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection) {
        parent::configureRoutes($collection);
 
        $collection->add('activate',
            $this->getRouterIdParameter().'/activate')
        ;
 
        $collection->add('deactivate',
            $this->getRouterIdParameter().'/deactivate')
        ;
        
        $collection->remove('create');
    }
}