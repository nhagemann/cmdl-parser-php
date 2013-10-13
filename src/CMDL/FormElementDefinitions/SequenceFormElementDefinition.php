<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class SequenceFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'sequence';

    protected $inserts = array();


    public function setInserts($inserts)
    {
        if (is_array($inserts))
        {
            $this->inserts = $inserts;
        }
        else
        {
            throw  new CMDLParserException('Parameter "inserts" of form element ' . $this->elementType . ' must be an array.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getInserts()
    {
        return $this->inserts;
    }

}
