<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\FormElementDefinitionCollection;

class ClippingDefinition extends FormElementDefinitionCollection
{

    public function getProperties()
    {
        $properties = parent::getProperties();

        $properties = array_unique(array_merge(Parser::$superProperties, $properties));

        return $properties;
    }

}