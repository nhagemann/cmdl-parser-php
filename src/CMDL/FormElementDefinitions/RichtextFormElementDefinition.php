<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinitions\TextareaFormElementDefinition;

class RichtextFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'richtext';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}
