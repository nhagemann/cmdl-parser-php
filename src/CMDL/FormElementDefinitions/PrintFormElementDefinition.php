<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class PrintFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'print';

    protected $property = null;


    public function setDisplay($display)
    {
        $this->display = $display;
    }


    public function getDisplay()
    {
        return $this->display;
    }


    public function setProperty($property)
    {
        $this->property = $property;
    }


    public function getProperty()
    {
        return $this->property;
    }

    protected $display = null;




}
