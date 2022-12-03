<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\FormElementDefinitions\InsertFormElementDefinition;

class InsertAnnotation extends Annotation
{
    protected $annotationType = 'insert';


    public function apply()
    {

        $formElementDefinition = new InsertFormElementDefinition();

        $property = false;

        if ($this->hasList(3)) {
            $property = true;
            $formElementDefinition->setWorkspaces($this->getList(2));
            $formElementDefinition->setLanguages($this->getList(3));
        } elseif ($this->hasList(2)) {
            $formElementDefinition->setWorkspaces($this->getList(1));
            $formElementDefinition->setLanguages($this->getList(2));
        } elseif ($this->hasList(1)) {
            $property = true;
        }

        if ($property) {
            // it's property dependent insert
            if (!$this->hasParam(1)) {
                throw new CMDLParserException('Missing mandatory parameter property for annotation @insert.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }

            $formElementDefinition->setPropertyName($this->getParam(1));
            $formElementDefinition->setInsertConditions($this->getList(1));
        } else {
            if (!$this->hasParam(1)) {
                throw new CMDLParserException('Missing mandatory parameter clipping name for annotation @insert.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }

            $formElementDefinition->setClippingName($this->getParam(1));
        }

        $this->currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);

        return $this->dataTypeDefinition;
    }
}
