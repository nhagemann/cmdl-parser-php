<?php

namespace CMDL\FormElementDefinitions;

class HTMLFormElementDefinition extends TextareaFormElementDefinition
{
    protected $elementType = 'html';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}
