<?php
namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;
use CMDL\CMDLParserException;

class TextareaFormElementDefinition extends TextfieldFormElementDefinition
{

    protected $elementType = 'textarea';

    protected $size = 'L';
    protected $rows = 10;


    public function setRows($rows)
    {
        if (is_numeric($rows))
        {
            $this->rows = $rows;
        }
        else
        {
            throw  new CMDLParserException('Parameter "rows" of form element ' . $this->elementType . ' must be a number.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getRows()
    {
        return $this->rows;
    }

}
