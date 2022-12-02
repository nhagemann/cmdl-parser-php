<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class PrintFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'print';


    public function setDisplay($display)
    {
        $this->display = $display;
    }


    public function getDisplay()
    {
        return $this->display;
    }


    protected $display = null;
}
