<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class TabStartFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'tab';

    protected $opened = false;


    public function setOpened($opened)
    {
        if (is_bool($opened))
        {
            $this->opened = $opened;
        }
        else
        {
            throw new CMDLParserException('',CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getOpened()
    {
        return $this->opened;
    }

}
