<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinitions\TextareaFormElementDefinition;

class MarkdownFormElementDefinition extends TextareaFormElementDefinition
{
    protected $elementType = 'markdown';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}//end class
