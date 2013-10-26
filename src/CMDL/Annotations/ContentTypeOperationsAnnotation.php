<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class ContentTypeOperationsAnnotation extends Annotation
{

    protected $annotationType = 'operations';


    public function apply()
    {

        if (!$this->hasList(1))
        {
            throw new CMDLParserException('Missing mandatory list for annotation @operations.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $this->contentTypeDefinition->setOperations(array_keys($this->getList(1)));

        return $this->contentTypeDefinition;
    }

}
