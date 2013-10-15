<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class GeolocationFormElementDefinition extends FormElementDefinition
{

    protected $elementType = 'geolocation';

    protected $maxValueLength = 20;
}
