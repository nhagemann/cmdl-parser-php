<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinitions\TextareaFormElementDefinition;

class HTMLFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'html';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;

}