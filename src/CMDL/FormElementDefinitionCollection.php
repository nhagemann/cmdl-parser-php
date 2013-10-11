<?php

namespace CMDL;

class FormElementDefinitionCollection
{

    protected $name;
    protected $fields = array();
    protected $namedFields = array();


    public function __construct($name = 'default')
    {
        $this->setName($name);

    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }


    public function addFormElementDefinition($definition)
    {
        if ($definition->getName() != null)
        {
            $this->namedFields[$definition->getName()] = $definition;
        }
        $this->fields[] = $definition;
    }


    public function getFormElementDefinition($name = 'default')
    {
        if (array_key_exists($name, $this->namedFields))
        {
            return $this->namedFields[$name];
        }

        throw new CMDLParserException('', CMDLParserException::CMDL_FIELD_NOT_DEFINED);
    }


    public function getFormElementDefinitions()
    {
        return $this->fields;
    }

}