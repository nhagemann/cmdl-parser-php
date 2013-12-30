<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class InsertFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'insert';

    protected $insertionName = null;

    protected $propertyName = null;

    protected $insertConditions = array();


    public function __construct()
    {

    }


    public function setInsertionName($insertionName)
    {
        $this->insertionName = $insertionName;
    }


    public function getInsertionName($value=null)
    {
        if ($this->getPropertyName()!=null)
        {
            if (array_key_exists($value,$this->insertConditions))
            {
                return $this->insertConditions[$value];
            }
            return null;
        }
        return $this->insertionName;
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
