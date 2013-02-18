<?php

namespace Smirik\AdminBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('groups')
            ->add('plainPassword')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'FOS\UserBundle\Propel\User',
        );
    }

    public function getName()
    {
        return 'User';
    }

}

