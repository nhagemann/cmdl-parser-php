<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class ReferenceFormElementDefinition extends FormElementDefinition
{

    protected $type = 'dropdown';

    protected $elementType = 'reference';

    protected $contentType = null;

    protected $workspace = 'default';

    protected $order = 'name';



    public function setType($type)
    {
        if (in_array($type, array( 'dropdown', 'radio', 'toggle' )))
        {
            $this->type = $type;
        }
        else
        {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of dropdown, radio, toggle.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getType()
    {
        return $this->type;
    }


    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    public function getContentType()
    {
        return $this->contentType;
    }


    public function setOrder($order)
    {
        $this->order = $order;
    }


    public function getOrder()
    {
        return $this->order;
    }


    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }


    public function getWorkspace()
    {
        return $this->workspace;
    }

}
