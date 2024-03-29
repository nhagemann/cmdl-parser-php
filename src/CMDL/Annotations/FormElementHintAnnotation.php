<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class FormElementHintAnnotation extends Annotation
{
    protected $annotationType = 'hint';

    public function apply()
    {
        if (!$this->hasParam(1)) {
            throw new CMDLParserException('Missing mandatory parameter property for annotation @hint.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if (!$this->hasParam(2)) {
            throw new CMDLParserException('Missing mandatory parameter value for annotation @hint.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        if (!$this->dataTypeDefinition->hasProperty($this->getParam(1))) {
            throw new CMDLParserException('Unknown property ' . $this->getParam(1) . ' within annotation @hint.', CMDLParserException::CMDL_UNKNOWN_PROPERTY);
        }

        $this->currentFormElementDefinitionCollection->getFormElementDefinition($this->getParam(1))
            ->setHint($this->getParam(2));

        return $this->dataTypeDefinition;
    }
}
