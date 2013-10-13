<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\CMDLParserException;

class RichtextFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'richtext';

    protected $size = 'L';

    protected $rows = 10;

}
