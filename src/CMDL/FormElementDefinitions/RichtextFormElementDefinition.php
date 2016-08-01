<?php

namespace CMDL\FormElementDefinitions;


class RichtextFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'richtext';

    protected $size = 'L';

    protected $rows = 10;

    protected $maxValueLength = 256;
}
