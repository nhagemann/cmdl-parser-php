<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ClippingDefinition;
use CMDL\InsertionDefinition;

class DataTypeDefinition
{

    protected $name = null;
    protected $title = null;
    protected $description = null;

    protected $languages = array( 'default' => 'Default' );

    protected $workspaces = array( 'default' => 'Default' );

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

        throw new CMDLParserException('Data type' . rtrim(' ' . $this->getName()) . ' has no clipping "' . $name . '"', CMDLParserException::CMDL_CLIPPING_NOT_DEFINED);
    }


    public function hasClippingDefinition($name)
    {
        if (array_key_exists($name, $this->clippings))
        {
            return true;
        }

        return false;
    }

    public function getClippingDefinitions()
    {
        return $this->clippings;
    }

    public function getListClippingDefinition($name = 'list')
    {
        if ($name != 'list')
        {
            if ($this->hasClippingDefinition($name))
            {
                return $this->getClippingDefinition($name);
            }
        }

        if ($this->hasClippingDefinition('list'))
        {
            return $this->getClippingDefinition('list');
        }

        return $this->getClippingDefinition('default');
    }


    public function getInsertClippingDefinition($name = 'insert')
    {
        if ($name != 'insert')
        {
            if ($this->hasClippingDefinition($name))
            {
                return $this->getClippingDefinition($name);
            }
        }

        if ($this->hasClippingDefinition('insert'))
        {
            return $this->getClippingDefinition('insert');
        }

        return $this->getClippingDefinition('default');
    }


    public function getEditClippingDefinition($name = 'edit')
    {
        if ($name != 'edit')
        {
            if ($this->hasClippingDefinition($name))
            {
                return $this->getClippingDefinition($name);
            }
        }

        if ($this->hasClippingDefinition('edit'))
        {
            return $this->getClippingDefinition('edit');
        }

        return $this->getClippingDefinition('default');
    }


    public function getDuplicateClippingDefinition($name = 'duplicate')
    {
        if ($name != 'duplicate')
        {
            if ($this->hasClippingDefinition($name))
            {
                return $this->getClippingDefinition($name);
            }
        }

        if ($this->hasClippingDefinition('duplicate'))
        {
            return $this->getClippingDefinition('duplicate');
        }

        return $this->getClippingDefinition('default');
    }


    public function getExchangeClippingDefinition($name = 'exchange')
    {
        if ($name != 'exchange')
        {
            if ($this->hasClippingDefinition($name))
            {
                return $this->getClippingDefinition($name);
            }
        }

        if ($this->hasClippingDefinition('exchange'))
        {
            return $this->getClippingDefinition('exchange');
        }

        return $this->getClippingDefinition('default');
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

        throw new CMDLParserException('Insertion '.$name.' not defined.', CMDLParserException::CMDL_INSERTION_NOT_DEFINED);
    }


    public function addInsertionDefinition(InsertionDefinition $definition)
    {
        $this->insertions[$definition->getName()] = $definition;
    }

    public function hasInsertionDefinition($name)
    {
        return  array_key_exists($name, $this->insertions);
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
            return in_array($property, $this->getProperties());
        }

    }


    public function getProperties($clippingName = null)
    {

        $inserts = array();


        if ($clippingName)
        {
            $clippingDefinition = $this->getClippingDefinition($clippingName);

            $properties = $clippingDefinition->getProperties();

            $inserts = $clippingDefinition->getPossibleInsertionNames();
        }
        else
        {
            $properties = array();
            foreach ($this->clippings as $clippingDefinition)
            {
                $properties = array_merge($properties, $clippingDefinition->getProperties());
                $inserts    = array_merge($inserts, $clippingDefinition->getPossibleInsertionNames());
            }

        }


        $inserts = array_unique($inserts);

        foreach ($inserts as $insertionName)
        {

            $insertionDefinition = $this->getInsertionDefinition($insertionName);

            $properties = array_merge($properties, $insertionDefinition->getProperties());

        }

        $properties = array_unique($properties);

        return $properties;
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


    public function setLanguages(array $languages)
    {
        $this->languages = $languages;
    }


    public function getLanguages()
    {
        return $this->languages;
    }


    public function hasLanguages()
    {
        if ($this->languages == null || count($this->languages) < 2)
        {
            return false;
        }

        return true;
    }


    public function setWorkspaces($workspaces)
    {
        $this->workspaces = $workspaces;
    }


    public function getWorkspaces()
    {
        return $this->workspaces;
    }


    public function hasWorkspaces()
    {
        if ($this->workspaces == null || count($this->workspaces) < 2)
        {
            return false;
        }

        return true;
    }

}