<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class DataTypeDescriptionAnnotation extends Annotation
{
    protected $annotationType = 'description';

    public function apply()
    {
        if ($this->hasParam(1)) {
            $this->dataTypeDefinition->setDescription($this->getParam(1));
        } else {
            throw new CMDLParserException('Missing mandatory parameter title for annotation @description.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        return $this->dataTypeDefinition;
    }
}
