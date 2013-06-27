<?php

namespace Smirik\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('groups', 'model', array(
                  'class'    => 'FOS\UserBundle\Propel\Group',
                  'multiple' => true,
                  'required' => false,
            ))
            ->add('plainPassword', 'text', array(
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'FOS\UserBundle\Propel\User'
            )
        );
    }

    public function getName()
    {
        return 'User';
    }

}
