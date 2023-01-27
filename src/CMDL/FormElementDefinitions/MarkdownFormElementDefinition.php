<?php

namespace CMDL\FormElementDefinitions;

class MarkdownFormElementDefinition extends TextareaFormElementDefinition
{
    protected $elementType = 'markdown';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}
