<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class TableFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'table';

    protected $columnHeadings = [];

    protected $widths = [];

    protected $maxValueLength = 256;

    public function __construct($name, $params, $lists)
    {
        if (isset($lists[0])) {
            $this->setColumnHeadings($lists[0]);
        } else {
            throw  new CMDLParserException('Missing mandatory list "headings" of form element ' . $this->elementType, CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if (isset($lists[1])) {
            $this->setWidths($lists[1]);
        }

        parent::__construct($name, $params, $lists);
    }

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
