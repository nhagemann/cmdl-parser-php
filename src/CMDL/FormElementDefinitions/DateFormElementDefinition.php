<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class DateFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'timestamp';

    protected $type = 'long';

    protected $init = null;


    public function setType($type)
    {
        if (in_array($type, array( 'long', 'short', 'datetime', 'datetimeseconds' )))
        {
            $this->type = $type;
        }
        else
        {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of datetime, datetimeseconds', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getType()
    {
        return $this->type;
    }


    public function setInit($init)
    {
        if (in_array($init, array( 'now', 'today' )))
        {
            $this->init = $init;
        }
        else
        {
            throw  new CMDLParserException('Parameter "init" of form element ' . $this->elementType . ' must be one of datetime, datetimeseconds', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getInit()
    {
        return $this->init;
    }

}
