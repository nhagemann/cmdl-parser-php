<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class InsertFormElementDefinition extends FormElementDefinition
{
    protected $elementType = 'insert';

    protected $clippingName = null;

    protected $propertyName = null;

    protected $insertConditions = [];

    protected $workspaces = [];

    protected $languages = [];

    public function setClippingName($clippingName)
    {
        $this->clippingName = $clippingName;
    }

    public function getClippingName($value = null)
    {
        if ($this->getPropertyName() != '') {
            if (array_key_exists($value, $this->insertConditions)) {
                return $this->insertConditions[$value];
            }

            return null;
        }

        return $this->clippingName;
    }

    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function setInsertConditions($insertConditions)
    {
        $this->insertConditions = $insertConditions;
    }

    public function getInsertConditions()
    {
        return $this->insertConditions;
    }

    /**
     * @return array
     */
    public function getWorkspaces()
    {
        return $this->workspaces;
    }

    /**
     * @param array $workspaces
     */
    public function setWorkspaces($workspaces)
    {
        $this->workspaces = array_keys($workspaces);
    }

    public function hasWorkspacesRestriction()
    {
        return (bool) count($this->getWorkspaces());
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = array_keys($languages);
    }

    public function hasLanguagesRestriction()
    {
        return (bool) count($this->getLanguages());
    }
}
