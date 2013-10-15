<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\TextareaFormElementDefinition;
use CMDL\CMDLParserException;

class CMDLFormElementDefinition extends TextareaFormElementDefinition
{

    protected $elementType = 'cmdl';

    protected $size = 'L';

    protected $rows = 10;



}