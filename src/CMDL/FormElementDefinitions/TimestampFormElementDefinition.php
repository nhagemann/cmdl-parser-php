<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TimestampFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'timestamp';

    protected $type = 'datetime';

    protected $init = null;

    protected $maxValueLength = 10;


    public function setType($type)
    {
        if (in_array($type, [ 'datetime', 'full' ])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of datetime, full', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }//end setType()


    public function getType()
    {
        return $this->type;
    }//end getType()


    public function setInit($init)
    {
        if (in_array($init, [ 'now', 'today' ])) {
            $this->init = $init;
        } else {
            throw  new CMDLParserException('Parameter "init" of form element ' . $this->elementType . ' must be one of now, today', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }//end setInit()


    public function getInit()
    {
        return $this->init;
    }//end getInit()
}//end class
