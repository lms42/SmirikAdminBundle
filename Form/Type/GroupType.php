<?php

namespace Smirik\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('users')
    ;
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'data_class' => 'FOS\UserBundle\Propel\Group',
    );
  }

  public function getName()
  {
    return 'Group';
  }
  
}

