<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class DataTypeTitleAnnotation extends Annotation
{
    protected $annotationType = 'title';

    public function apply()
    {
        if ($this->hasParam(1)) {
            $this->dataTypeDefinition->setTitle($this->getParam(1));
        } else {
            throw new CMDLParserException('Missing mandatory parameter title for annotation @title.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        return $this->dataTypeDefinition;
    }
}
