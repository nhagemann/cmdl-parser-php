<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;

class ContentTypeNameAnnotation extends Annotation
{
    protected $annotationType = 'name';

    /**
     * @var ContentTypeDefinition|null
     */
    protected $dataTypeDefinition = null;


    public function apply()
    {
        if ($this->hasParam(1)) {
            $this->dataTypeDefinition->setNamingPattern($this->getParam(1));
        } else {
            throw new CMDLParserException('Missing mandatory parameter title for annotation @title.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        return $this->dataTypeDefinition;
    }
}
