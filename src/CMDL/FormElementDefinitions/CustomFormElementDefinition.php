<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class CustomFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'custom';

    protected $type = null;

    protected $maxValueLength = 256;


    public function __construct($name, $params, $lists)
    {
        $type = array_shift($params);

        $this->setType($type);

        parent::__construct($name, $params, $lists);
    }//end __construct()


    public function setType($type)
    {
        $this->type = $type;
    }//end setType()


    public function getType()
    {
        return $this->type;
    }//end getType()
}//end class
