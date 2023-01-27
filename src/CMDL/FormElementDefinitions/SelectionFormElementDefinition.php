<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class SelectionFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'selection';

    protected $type = 'dropdown';

    protected $options = [];

    public function __construct($name, $params = [], $lists = [])
    {
        if (isset($params[0])) {
            $this->setType($params[0]);
        }

        if (isset($lists[0])) {
            $this->setOptions($lists[0]);
        }

        parent::__construct($name, $params, $lists);
    }

    public function setType($type)
    {
        if (in_array($type, ['dropdown', 'radio', 'toggle'])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of dropdown, radio, toggle.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

    public function getType()
    {
        return $this->type;
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
