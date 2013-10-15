<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\CMDLParserException;

class MarkdownFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'markdown';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}
