<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeTitleAnnotation extends Annotation
{

    protected $annotationType = 'content-type-title';


    public function apply()
    {
        if ($this->hasParam(1))
        {
            $this->contentTypeDefinition->setTitle($this->getParam(1));
        }
        else
        {
            throw new CMDLParserException('Missing mandatory parameter title for annotation @content-type-title.',CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        return $this->contentTypeDefinition;
    }

}
