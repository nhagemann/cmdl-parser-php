<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

class FormElementCollectionHiddenPropertiesAnnotation extends Annotation
{
    protected $annotationType = 'hidden-properties';


    public function apply()
    {

        if (!$this->hasList(1)) {
            throw new CMDLParserException('Missing mandatory list for annotation @hidden-properties.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $this->currentFormElementDefinitionCollection->setHiddenProperties($this->getList(1));

        return $this->dataTypeDefinition;
    }
}
