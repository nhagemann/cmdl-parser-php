<?php

namespace CMDL;

use CMDL\FormElementDefinitions\InsertFormElementDefinition;

class FormElementDefinitionCollection
{
    protected $name;

    /**
     * @var FormElementDefinition[]
     */
    protected $fields = [];

    protected $namedFields = [];

    protected $properties = null;

    protected $mandatoryProperties = null;

    protected $uniqueProperties = null;

    protected $protectedProperties = null;

    protected $hiddenProperties = [];

    protected $parentDataTypeDefinition = null;


    public function __construct($name = 'default', $parentDataTypeDefinition = null)
    {
        $this->setName($name);
        $this->parentDataTypeDefinition = $parentDataTypeDefinition;
    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }


    public function addFormElementDefinition(FormElementDefinition $definition)
    {
        if ($definition->getName() != '') {
            $this->namedFields[$definition->getName()] = $definition;
        }

        $this->fields[] = $definition;

        $this->properties          = null;
        $this->mandatoryProperties = null;
        $this->uniqueProperties    = null;
        $this->protectedProperties = null;
    }


    /**
     * @param $name
     *
     * @return FormElementDefinition
     * @throws CMDLParserException
     */
    public function getFormElementDefinition($name)
    {
        if (array_key_exists($name, $this->namedFields)) {
            return $this->namedFields[$name];
        }

        throw new CMDLParserException(
            'Could not find a formelement named ' . $name,
            CMDLParserException::CMDL_FORMELEMENT_NOT_FOUND
        );
    }


    /**
     * @return FormElementDefinition[]
     */
    public function getFormElementDefinitions()
    {
        return $this->fields;
    }


    public function getProperties()
    {
        if ($this->properties) {
            return $this->properties;
        }

        $properties          = $this->getHiddenProperties();
        $mandatoryProperties = [];
        $uniqueProperties    = [];
        $protectedProperties = [];

        foreach ($this->fields as $formElementDefinition) {
            if ($formElementDefinition->getName()) {
                $properties[] = $formElementDefinition->getName();
                if ($formElementDefinition->isMandatory()) {
                    $mandatoryProperties[] = $formElementDefinition->getName();
                }

                if ($formElementDefinition->isUnique()) {
                    $uniqueProperties[] = $formElementDefinition->getName();
                }

                if ($formElementDefinition->isProtected()) {
                    $protectedProperties[] = $formElementDefinition->getName();
                }
            }
        }

        if ($this->parentDataTypeDefinition) {
            $clippings = $this->getNamesOfEventuallyInsertedClippings();
            foreach ($clippings as $clippingName) {
                $clippingDefinition = $this->parentDataTypeDefinition->getClippingDefinition($clippingName);

                $properties = array_merge($properties, $clippingDefinition->getProperties());
            }
        }

        $this->properties          = $properties;
        $this->mandatoryProperties = $mandatoryProperties;
        $this->uniqueProperties    = $uniqueProperties;
        $this->protectedProperties = $protectedProperties;

        return $this->properties;
    }


    public function getMandatoryProperties()
    {
        if (!$this->properties) {
            $this->getProperties();
        }

        return $this->mandatoryProperties;
    }


    public function getUniqueProperties()
    {
        if (!$this->properties) {
            $this->getProperties();
        }

        return $this->uniqueProperties;
    }


    public function getProtectedProperties()
    {
        if (!$this->properties) {
            $this->getProperties();
        }

        return $this->protectedProperties;
    }


    public function hasProperty($property)
    {
        if (in_array($property, $this->getProperties())) {
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


    public function getNamesOfEventuallyInsertedClippings()
    {

        $clippingNames = [];
        foreach ($this->getFormElementDefinitions() as $formElementDefinition) {
            if ($formElementDefinition->getFormElementType() == 'insert') {
                // @var $formElementDefinition InsertFormElementDefinition
                if ($formElementDefinition->getPropertyName() == '') {
                    $clippingNames[] = $formElementDefinition->getClippingName();
                } else {
                    $clippingNames = array_merge(
                        $clippingNames,
                        array_values($formElementDefinition->getInsertConditions())
                    );
                }
            }
        }

        return $clippingNames;
    }
}
