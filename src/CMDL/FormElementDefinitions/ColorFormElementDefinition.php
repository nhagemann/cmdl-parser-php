<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class ColorFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'color';

    protected $selectionType = 'free';

    protected $options = [];

    protected $maxValueLength = 6;

    public function __construct($name, $params = [], $lists = [])
    {
        if (isset($params[0])) {
            $this->setSelectionType($params[0]);
        }

        if (isset($lists[0])) {
            $this->setOptions($lists[0]);
        }

        parent::__construct($name, $params, $lists);
    }

    public function setSelectionType($selectionType)
    {
        if (in_array($selectionType, ['free', 'fixed'])) {
            $this->selectionType = $selectionType;
        } else {
            throw  new CMDLParserException('Parameter "selectionType" of form element ' . $this->elementType . ' must be one of free, fixed.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

    public function getSelectionType()
    {
        return $this->selectionType;
    }

    public function setOptions($options)
    {
        if (is_array($options)) {
            $this->options = $options;
        } else {
            throw  new CMDLParserException('Parameter "options" of form element ' . $this->elementType . ' must be an array.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

    public function getOptions()
    {
        return $this->options;
    }
}
