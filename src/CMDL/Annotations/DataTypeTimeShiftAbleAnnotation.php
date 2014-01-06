<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class DataTypeTimeShiftableAnnotation extends Annotation
{

    protected $annotationType = 'time-shiftable';


    public function apply()
    {
        $this->dataTypeDefinition->setTimeShiftable(true);

        return $this->dataTypeDefinition;
    }

}
