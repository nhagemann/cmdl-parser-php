<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TimeFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'time';

    protected $type = 'short';

    protected $init = null;

    protected $maxValueLength = 10;


    public function setType($type)
    {
        if (in_array($type, array( 'short', 'full' )))
        {
            $this->type = $type;
        }
        else
        {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of short, full', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getType()
    {
        return $this->type;
    }


    public function setInit($init)
    {
        if (in_array($init, array( 'now' )))
        {
            $this->init = $init;
        }
        else
        {
            throw  new CMDLParserException('Parameter "init" of form element ' . $this->elementType . ' must be "short" or skipped.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getInit()
    {
        return $this->init;
    }

}
