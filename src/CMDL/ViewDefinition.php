<?php

namespace CMDL;

class ViewDefinition extends FormElementDefinitionCollection
{

    public function getProperties()
    {
        $properties = parent::getProperties();

        $properties = array_unique(array_merge(Parser::$superProperties, $properties));

        return $properties;
    }

}