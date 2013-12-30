<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitionCollection;

class ClippingDefinition extends FormElementDefinitionCollection
{

    public function getProperties()
    {
        $properties = parent::getProperties();
        $properties = array_unique(array_merge(Parser::$superProperties, $properties));

        return $properties;
    }


    public function getPossibleInsertionNames()
    {
        /* @var $formElementDefinition FormElementDefinition */
        $inserts = array();
        foreach ($this->getFormElementDefinitions() as $formElementDefinition)
        {
            if ($formElementDefinition->getFormElementType() == 'insert')
            {

                if ($formElementDefinition->getPropertyName() == null)
                {
                    $inserts[] = $formElementDefinition->getInsertionName();
                }
                else
                {
                    $inserts = $inserts + array_values($formElementDefinition->getInsertConditions());
                }
            }

        }
        return $inserts;
    }
}