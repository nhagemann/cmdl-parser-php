<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeDescriptionAnnotation extends Annotation
{

    protected $annotationType = 'content-type-description';


    public function apply()
    {
        $this->contentTypeDefinition->setDescription($this->getParam(1));

        return $this->contentTypeDefinition;
    }

}
