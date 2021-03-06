<?php

namespace Smirik\AdminBundle\Model;

use Smirik\AdminBundle\Model\om\BaseProfile;

class Profile extends BaseProfile
{
    
    public function getName()
    {
        $name = $this->getLastName().' '.$this->getFirstName();
        if ($name == ' ')
        {
            return $this->getUser()->getUsername();
        }
        return $name;
    }

    public function getTeacherName()
    {
        $name = $this->getFirstName().' '.$this->getLastName();
        if ($name == ' ')
        {
            return $this->getUser()->getUsername();
        }
        return $name;
    }
    
}
