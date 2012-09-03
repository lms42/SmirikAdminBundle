<?php

namespace Smirik\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username')
	    ->add('groups', 'model', array(
	    	'class' => 'FOS\UserBundle\Propel\Group',
				'multiple' => true,
	    ))
      ->add('email')
      ->add('enabled')
      ->add('plainpassword', 'text', array('required' => false))
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

