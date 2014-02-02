<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class CheckboxFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'checkbox';

    protected $maxValueLength = 1;


    public function setLegend($legend)
    {
        $this->legend = $legend;
    }


    public function getLegend()
    {
        return $this->legend;
    }


    protected $legend = null;

}
