<?php

namespace CMDL;

use CMDL\CMDLParserException;
use CMDL\ViewDefinition;
use CMDL\ClippingDefinition;

class DataTypeDefinition
{

    protected $name = null;
    protected $title = null;
    protected $description = null;

    protected $languages = array( 'default' => 'Default' );

    protected $workspaces = array( 'default' => 'Default' );

    protected $cmdl = null;
    protected $views = array();
    protected $clippings = array();


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
     * @return ViewDefinition
     * @throws CMDLParserException
     */
    public function getViewDefinition($name = 'default')
    {
        if (array_key_exists($name, $this->views))
        {
            return $this->views[$name];
        }

        throw new CMDLParserException('Data type' . rtrim(' ' . $this->getName()) . ' has no view "' . $name . '"', CMDLParserException::CMDL_VIEW_NOT_DEFINED);
    }


    public function hasViewDefinition($name)
    {
        if (array_key_exists($name, $this->views))
        {
            return true;
        }

        return false;
    }

    public function getViewDefinitions()
    {
        return $this->views;
    }

    public function getListViewDefinition($name = 'list')
    {
        if ($name != 'list')
        {
            if ($this->hasViewDefinition($name))
            {
                return $this->getViewDefinition($name);
            }
        }

        if ($this->hasViewDefinition('list'))
        {
            return $this->getViewDefinition('list');
        }

        return $this->getViewDefinition('default');
    }


    public function getInsertViewDefinition($name = 'insert')
    {
        if ($name != 'insert')
        {
            if ($this->hasViewDefinition($name))
            {
                return $this->getViewDefinition($name);
            }
        }

        if ($this->hasViewDefinition('insert'))
        {
            return $this->getViewDefinition('insert');
        }

        return $this->getViewDefinition('default');
    }


    public function getEditViewDefinition($name = 'edit')
    {
        if ($name != 'edit')
        {
            if ($this->hasViewDefinition($name))
            {
                return $this->getViewDefinition($name);
            }
        }

        if ($this->hasViewDefinition('edit'))
        {
            return $this->getViewDefinition('edit');
        }

        return $this->getViewDefinition('default');
    }


    public function getDuplicateViewDefinition($name = 'duplicate')
    {
        if ($name != 'duplicate')
        {
            if ($this->hasViewDefinition($name))
            {
                return $this->getViewDefinition($name);
            }
        }

        if ($this->hasViewDefinition('duplicate'))
        {
            return $this->getViewDefinition('duplicate');
        }

        return $this->getViewDefinition('default');
    }


    public function getExchangeViewDefinition($name = 'exchange')
    {
        if ($name != 'exchange')
        {
            if ($this->hasViewDefinition($name))
            {
                return $this->getViewDefinition($name);
            }
        }

        if ($this->hasViewDefinition('exchange'))
        {
            return $this->getViewDefinition('exchange');
        }

        return $this->getViewDefinition('default');
    }


    public function addViewDefinition(ViewDefinition $definition)
    {
        $this->views[$definition->getName()] = $definition;
    }


    public function getClippingDefinition($name)
    {
        if (array_key_exists($name, $this->clippings))
        {
            return $this->clippings[$name];
        }

        throw new CMDLParserException('Clipping '.$name.' not defined.', CMDLParserException::CMDL_CLIPPING_NOT_DEFINED);
    }


    public function addClippingDefinition(ClippingDefinition $definition)
    {
        $this->clippings[$definition->getName()] = $definition;
    }

    public function hasClippingDefinition($name)
    {
        return  array_key_exists($name, $this->clippings);
    }

    public function hasProperty($property, $viewName = null)
    {
        // include super properties
        if (in_array($property, Parser::$superProperties))
        {
            return true;
        }

        if ($viewName)
        {
            return $this->getViewDefinition($viewName)->hasProperty($property);
        }
        else
        {
            return in_array($property, $this->getProperties());
        }

    }


    public function getProperties($viewName = null)
    {

        $inserts = array();


        if ($viewName)
        {
            $viewDefinition = $this->getViewDefinition($viewName);

            $properties = $viewDefinition->getProperties();

            $inserts = $viewDefinition->getNamesOfEventuallyInsertedClippings();
        }
        else
        {
            $properties = array();
            foreach ($this->views as $viewDefinition)
            {
                $properties = array_merge($properties, $viewDefinition->getProperties());
                $inserts    = array_merge($inserts, $viewDefinition->getNamesOfEventuallyInsertedClippings());
            }

        }


        $inserts = array_unique($inserts);

        foreach ($inserts as $clippingName)
        {

            $clippingDefinition = $this->getClippingDefinition($clippingName);

            $properties = array_merge($properties, $clippingDefinition->getProperties());

        }

        $properties = array_unique($properties);

        return $properties;
    }


    public function getMandatoryProperties($viewName)
    {

        $viewDefinition = $this->getViewDefinition($viewName);

        return $viewDefinition->getMandatoryProperties();

    }


    public function getUniqueProperties($viewName)
    {

        $viewDefinition = $this->getViewDefinition($viewName);

        return $viewDefinition->getUniqueProperties();

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
    
    public function hasLanguage($language)
    {
        if (!$this->hasLanguages())
        {
            if ($language == 'default')
            {
                return true;
            }

            return false;
        }
        if (array_key_exists($language, $this->languages))
        {
            return true;
        }

        return false;
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

    public function hasWorkspace($workspace)
    {
        if (!$this->hasWorkspaces())
        {
            if ($workspace == 'default')
            {
                return true;
            }

            return false;
        }
        if (array_key_exists($workspace, $this->workspaces))
        {
            return true;
        }

        return false;
    }    

}
