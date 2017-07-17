<?php

namespace AppBundle\Form;

use AppBundle\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category')
                ->add('type', 'choice', array('choices' => Job::getTypes(), 'expanded' => true))
                ->add('company')
                ->add('logo', 'file', array('required' => false, 'label' => 'Company logo'))
                ->add('url', null, array('required' => false))
                ->add('position')
                ->add('location')
                ->add('description')
                ->add('howToApply', null, array('label' => 'How to apply?'))
                ->add('isPublic', null, array('label' => 'Public?'))
                ->add('email');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Job'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_job';
    }


}
