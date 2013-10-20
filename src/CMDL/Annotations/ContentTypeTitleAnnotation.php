<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeTitleAnnotation extends Annotation
{

    protected $annotationType = 'content-type-title';


    public function apply()
    {
        $this->contentTypeDefinition->setTitle($this->getParam(1));

        return $this->contentTypeDefinition;
    }

}
