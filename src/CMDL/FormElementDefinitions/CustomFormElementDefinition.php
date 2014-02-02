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
    }


    public function setType($type)
    {
        $this->type = $type;
    }


    public function getType()
    {
        return $this->type;
    }

}
