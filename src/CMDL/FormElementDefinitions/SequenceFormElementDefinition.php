<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class SequenceFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'sequence';

    protected $inserts = [];

    protected $maxValueLength = 256;

    public function setInserts($inserts)
    {
        if (is_array($inserts)) {
            $this->inserts = $inserts;
        } else {
            throw  new CMDLParserException('Parameter "inserts" of form element ' . $this->elementType . ' must be an array.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

    public function getInserts()
    {
        return $this->inserts;
    }
}
