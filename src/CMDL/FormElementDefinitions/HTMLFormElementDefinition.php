<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\CMDLParserException;

class HTMLFormElementDefinition extends TextareaFormElementDefinition
{

    protected $type = 'html';

    protected $size = 'L';

    protected $rows = 10;

}