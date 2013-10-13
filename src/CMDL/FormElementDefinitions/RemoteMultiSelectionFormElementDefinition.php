<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteMultiSelectionFormElementDefinition extends RemoteSelectionFormElementDefinition
{

    protected $elementType = 'remote-multiselection';

    protected $type = 'list';


    public function setType($type)
    {
        if (in_array($type, array( 'list', 'checkbox' )))
        {
            $this->type = $type;
        }
        else
        {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of list, checkbox.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

}
