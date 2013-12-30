<?php

namespace CMDL;

use CMDL\FormElementDefinition;

class FormElementDefinitionCollection
{

    protected $name;
    protected $fields = array();
    protected $namedFields = array();

    protected $properties = null;
    protected $mandatoryProperties = null;
    protected $uniqueProperties = null;

    protected $hiddenProperties = array();


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

        $this->properties          = null;
        $this->mandatoryProperties = null;
        $this->uniqueProperties    = null;
    }


    /**
     * @param $name
     *
     * @return FormElementDefinition
     * @throws CMDLParserException
     */
    public function getFormElementDefinition($name)
    {
        if (array_key_exists($name, $this->namedFields))
        {
            return $this->namedFields[$name];
        }

        throw new CMDLParserException('Could not find a formelement named ' . $name, CMDLParserException::CMDL_FORMELEMENT_NOT_FOUND);
    }


    public function getFormElementDefinitions()
    {
        return $this->fields;
    }


    public function getProperties()
    {
        if ($this->properties)
        {
            return $this->properties;
        }
        $properties          = $this->getHiddenProperties();
        $mandatoryProperties = array();
        $uniqueProperties    = array();

        foreach ($this->fields as $formElementDefinition)
        {
            if ($formElementDefinition->getName())
            {
                $properties[] = $formElementDefinition->getName();
                if ($formElementDefinition->isMandatory())
                {
                    $mandatoryProperties[] = $formElementDefinition->getName();
                }
                if ($formElementDefinition->isUnique())
                {
                    $uniqueProperties[] = $formElementDefinition->getName();
                }
            }
        }
        $this->properties          = $properties;
        $this->mandatoryProperties = $mandatoryProperties;
        $this->uniqueProperties    = $uniqueProperties;

        return $this->properties;
    }


    public function getMandatoryProperties()
    {
        if (!$this->properties)
        {
            $this->getProperties();
        }

        return $this->mandatoryProperties;
    }


    public function getUniqueProperties()
    {
        if (!$this->properties)
        {
            $this->getProperties();
        }

        return $this->uniqueProperties;
    }


    public function hasProperty($property)
    {
        if (in_array($property, $this->getProperties()))
        {
            return true;
        }

        return false;
    }


    public function setHiddenProperties($hiddenProperties)
    {
        $this->hiddenProperties = $hiddenProperties;
    }


    public function getHiddenProperties()
    {
        return $this->hiddenProperties;
    }

}