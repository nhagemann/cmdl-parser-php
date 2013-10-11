<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\CMDLParserException;

class MarkdownFormElementDefinition extends TextareaFormElementDefinition
{

    protected $type = 'markdown';

    protected $size = 'L';

    protected $rows = 10;

}
