<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class InsertFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'insert';

    protected $clippingName = null;

    protected $propertyName = null;

    protected $insertConditions = array();


    public function __construct()
    {

    }


    public function setClippingName($clippingName)
    {
        $this->clippingName = $clippingName;
    }


    public function getClippingName($value=null)
    {
        if ($this->getPropertyName()!='')
        {
            if (array_key_exists($value,$this->insertConditions))
            {
                return $this->insertConditions[$value];
            }
            return null;
        }
        return $this->clippingName;
    }


    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }


    public function getPropertyName()
    {
        return $this->propertyName;
    }


    public function setInsertConditions($insertConditions)
    {
        $this->insertConditions = $insertConditions;
    }


    public function getInsertConditions()
    {
        return $this->insertConditions;
    }

}
