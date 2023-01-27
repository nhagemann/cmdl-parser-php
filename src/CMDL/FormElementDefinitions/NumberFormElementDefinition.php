<?php

namespace CMDL\FormElementDefinitions;

use CMDL\CMDLParserException;
use CMDL\FormElementDefinition;

class NumberFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'number';

    protected $digits = 0;

    protected $unit = null;

    public function setDigits($digits)
    {
        if (is_numeric($digits)) {
            $this->digits = $digits;
        } else {
            throw  new CMDLParserException('Parameter "digits" of form element ' . $this->elementType . ' must be a number.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }

    public function getDigits()
    {
        return $this->digits;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function getUnit()
    {
        return $this->unit;
    }
}
