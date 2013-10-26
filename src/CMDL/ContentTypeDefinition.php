<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ClippingDefinition;
use CMDL\InsertionDefinition;

class ContentTypeDefinition
{

    protected $name = null;
    protected $title = null;
    protected $description = null;


    protected $languages = null;
    protected $subtypes = null;
    protected $statusList = null;

    protected $cmdl = null;
    protected $clippings = array();
    protected $insertions = array();


    public function __construct($name = null)
    {
        $this->name = $name;
    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getName()
    {
        return $this->name;
    }


    public function setDescription($description)
    {
        $this->description = $description;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setTitle($title)
    {
        $this->title = $title;
    }


    public function getTitle()
    {
        return $this->title;
    }


    public function setCMDL($s)
    {
        $this->cmdl = $s;
    }


    public function getCMDL()
    {
        return $this->cmdl;
    }


    /**
     * @param string $name
     *
     * @return ClippingDefinition
     * @throws CMDLParserException
     */
    public function getClippingDefinition($name = 'default')
    {
        if (array_key_exists($name, $this->clippings))
        {
            return $this->clippings[$name];
        }

        throw new CMDLParserException('Content type' . rtrim(' ' . $this->getName()) . ' has no clipping "' . $name . '"', CMDLParserException::CMDL_CLIPPING_NOT_DEFINED);
    }


    public function addClippingDefinition(ClippingDefinition $definition)
    {
        $this->clippings[$definition->getName()] = $definition;
    }


    public function getInsertionDefinition($name)
    {
        if (array_key_exists($name, $this->insertions))
        {
            return $this->insertions[$name];
        }

        throw new CMDLParserException('', CMDLParserException::CMDL_INSERTION_NOT_DEFINED);
    }


    public function addInsertionDefinition(InsertionDefinition $definition)
    {
        $this->insertions[$definition->getName()] = $definition;
    }


    public function hasProperty($property, $clippingName = null)
    {
        // include super properties
        if (in_array($property, Parser::$superProperties))
        {
            return true;
        }

        if ($clippingName)
        {
            return $this->getClippingDefinition($clippingName)->hasProperty($property);
        }
        else
        {
            return in_array($property,$this->getProperties());
        }

    }


    public function getProperties($clippingName = null)
    {
        if ($clippingName)
        {
            $clippingDefinition = $this->getClippingDefinition($clippingName);

            return $clippingDefinition->getProperties();
        }
        else
        {
            $properties = array();
            foreach ($this->clippings as $clippingDefinition)
            {
                $properties = array_merge($properties, $clippingDefinition->getProperties());

            }

            return array_unique($properties);
        }
    }


    public function getMandatoryProperties($clippingName)
    {

        $clippingDefinition = $this->getClippingDefinition($clippingName);

        return $clippingDefinition->getMandatoryProperties();

    }


    public function getUniqueProperties($clippingName)
    {

        $clippingDefinition = $this->getClippingDefinition($clippingName);

        return $clippingDefinition->getUniqueProperties();

    }


    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }


    public function getLanguages()
    {
        return $this->languages;
    }


    public function setStatusList($statusList)
    {
        $this->statusList = $statusList;
    }


    public function getStatusList()
    {
        return $this->statusList;
    }


    public function setSubtypes($subtypes)
    {
        $this->subtypes = $subtypes;
    }


    public function getSubtypes()
    {
        return $this->subtypes;
    }


}