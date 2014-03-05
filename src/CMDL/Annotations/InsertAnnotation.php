<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;

use CMDL\FormElementDefinitions\InsertFormElementDefinition;
use CMDL\FormElementDefinitions\TextfieldFormElementDefinition;

class InsertAnnotation extends Annotation
{

    protected $annotationType = 'insert';


    public function apply()
    {

        $formElementDefinition = new InsertFormElementDefinition();

        if ($this->hasList(1)) // it's property dependent insert
        {
            if (!$this->hasParam(1))
            {
                throw new CMDLParserException('Missing mandatory parameter property for annotation @insert.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }
            $formElementDefinition->setPropertyName($this->getParam(1));
            $formElementDefinition->setInsertConditions($this->getList(1));
        }
        else
        {
            if (!$this->hasParam(1))
            {
                throw new CMDLParserException('Missing mandatory parameter insertion name for annotation @insert.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
            }
            $formElementDefinition->setInsertionName($this->getParam(1));
        }

        $this->currentFormElementDefinitionCollection->addFormElementDefinition($formElementDefinition);

        return $this->dataTypeDefinition;
    }

}
