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
    }//end setClippingName()


    public function getClippingName($value = null)
    {
        if ($this->getPropertyName() != '') {
            if (array_key_exists($value, $this->insertConditions)) {
                return $this->insertConditions[$value];
            }

            return null;
        }

        return $this->clippingName;
    }//end getClippingName()


    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }//end setPropertyName()


    public function getPropertyName()
    {
        return $this->propertyName;
    }//end getPropertyName()


    public function setInsertConditions($insertConditions)
    {
        $this->insertConditions = $insertConditions;
    }//end setInsertConditions()


    public function getInsertConditions()
    {
        return $this->insertConditions;
    }//end getInsertConditions()


    /**
     * @return array
     */
    public function getWorkspaces()
    {
        return $this->workspaces;
    }//end getWorkspaces()


    /**
     * @param array $workspaces
     */
    public function setWorkspaces($workspaces)
    {
        $this->workspaces = array_keys($workspaces);
    }//end setWorkspaces()


    public function hasWorkspacesRestriction()
    {
        return (bool) count($this->getWorkspaces());
    }//end hasWorkspacesRestriction()


    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }//end getLanguages()


    /**
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = array_keys($languages);
    }//end setLanguages()


    public function hasLanguagesRestriction()
    {
        return (bool) count($this->getLanguages());
    }//end hasLanguagesRestriction()
}//end class
