<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

/**
 * Class SelectionFormElementDefinition
 *
 * Examples:
 *  Customer = remote-selection http://demo.org/json/customers dropdown
 *
 * @package CMDL\FormElementDefinitions
 */
class RemoteSelectionFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'remote-selection';

    protected $type = 'dropdown';

    protected $url = null;


    public function __construct($name, $params = [], $lists = [])
    {

        if (isset($params[0])) {
            $this->setUrl($params[0]);
        }

        if (isset($params[1])) {
            $this->setType($params[1]);
        }

        parent::__construct($name, $params, $lists);
    }


    public function setType($type)
    {
        if (in_array($type, [ 'dropdown', 'radio', 'toggle' ])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of dropdown, radio, toggle.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getType()
    {
        return $this->type;
    }


    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function getUrl()
    {
        return $this->url;
    }
}
