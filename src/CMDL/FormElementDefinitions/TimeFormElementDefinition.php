<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TimeFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'time';

    protected $type = 'short';

    protected $init = null;

    protected $maxValueLength = 8;


    public function setType($type)
    {
        if (in_array($type, [ 'short', 'long' ])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of short, long', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }//end setType()


    public function getType()
    {
        return $this->type;
    }//end getType()


    public function setInit($init)
    {
        if (in_array($init, [ 'now' ])) {
            $this->init = $init;
        } else {
            throw  new CMDLParserException('Parameter "init" of form element ' . $this->elementType . ' must be "now" or skipped.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }//end setInit()


    public function getInit()
    {
        return $this->init;
    }//end getInit()
}//end class
