<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TableFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'table';

    protected $columnHeadings = array();

    protected $widths = array();


    public function setColumnHeadings($columnHeadings)
    {
        $this->columnHeadings = $columnHeadings;
    }


    public function getColumnHeadings()
    {
        return $this->columnHeadings;
    }


    public function setWidths($widths)
    {
        $this->widths = $widths;
    }


    public function getWidths()
    {
        return $this->widths;
    }

}