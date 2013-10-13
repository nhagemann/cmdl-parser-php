<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TextfieldFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'textfield';

    protected $size = 'L';


    public function setSize($size)
    {
        if (in_array($size, array( 'S', 'M', 'L', 'XL', 'XXL' )))
        {
            $this->size = $size;
        }
        else
        {
            throw  new CMDLParserException('Parameter "size" of form element ' . $this->elementType . ' must be one of S,M,L,XL,XXL', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }

    }


    public function getSize()
    {
        return $this->size;
    }

}
