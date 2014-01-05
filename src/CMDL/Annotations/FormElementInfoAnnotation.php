<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class FormElementInfoAnnotation extends Annotation
{

    protected $annotationType = 'info';


    public function apply()
    {

        if (!$this->hasParam(1))
        {
            throw new CMDLParserException('Missing mandatory parameter property for annotation @info.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if (!$this->hasParam(2))
        {
            throw new CMDLParserException('Missing mandatory parameter value for annotation @info.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if (!$this->dataTypeDefinition->hasProperty($this->getParam(1)))
        {
            throw new CMDLParserException('Unknown property ' . $this->getParam(1) . ' within annotation @info.', CMDLParserException::CMDL_UNKNOWN_PROPERTY);
        }

        $this->currentFormElementDefinitionCollection->getFormElementDefinition($this->getParam(1))
            ->setInfo($this->getParam(2));

        return $this->dataTypeDefinition;
    }

}
