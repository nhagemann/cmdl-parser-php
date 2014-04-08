<?php

namespace CMDL;

use CMDL\DataTypeDefinition;
use CMDL\CMDLParserException;

class ContentTypeDefinition extends DataTypeDefinition
{

    protected $subtypes = null;

    protected $statusList = null;

    protected $operations = array( 'list', 'get', 'update', 'insert', 'delete', 'revision', 'export', 'import' );

    protected $sortable = null;

    protected $timeShiftable = false;

    protected $synchronizedProperties = array( 'global' => array(), 'workspaces' => array(), 'languages' => array() );

    const SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL     = 'global';
    const SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES = 'workspaces';
    const SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES  = 'languages';


    public function hasStatusList()
    {
        if ($this->statusList == null || count($this->statusList) == 0)
        {
            return false;
        }

        return true;
    }


    public function setStatusList(array $statusList)
    {
        $this->statusList = $statusList;
    }


    public function getStatusList()
    {
        return $this->statusList;
    }


    public function hasSubtypes()
    {
        if ($this->subtypes == null || count($this->subtypes) == 0)
        {
            return false;
        }

        return true;
    }


    public function setSubtypes(array $subtypes)
    {
        $this->subtypes = $subtypes;
    }


    public function getSubtypes()
    {
        return $this->subtypes;
    }


    public function setOperations($operations)
    {
        foreach ($operations as $operation)
        {
            if (!in_array($operation, array( 'list', 'get', 'update', 'insert', 'delete', 'revision' )))
            {
                throw new CMDLParserException('Invalid operation settings ("' . $operation . '") for content type ' . rtrim(' ' . $this->getName()) . '. Must be one of list,get,update,revision.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }
        }

        $this->operations = $operations;

    }


    public function hasListOperation()
    {
        return in_array('list', $this->operations);
    }


    public function hasGetOperation()
    {
        return in_array('get', $this->operations);
    }


    public function hasUpdateOperation()
    {
        return in_array('update', $this->operations);
    }


    public function hasInsertOperation()
    {
        return in_array('insert', $this->operations);
    }


    public function hasDeleteOperation()
    {
        return in_array('delete', $this->operations);
    }


    public function hasRevisionOperations()
    {
        return in_array('revision', $this->operations);
    }


    public function hasExportOperation()
    {
        return in_array('export', $this->operations);
    }


    public function hasImportOperation()
    {
        return in_array('import', $this->operations);
    }


    public function setSortable($sortable)
    {
        if (!in_array($sortable, array( 'list', 'tree' )))
        {
            throw new CMDLParserException('Invalid sortables setting ("' . $sortable . '") for content type ' . rtrim(' ' . $this->getName()) . '. Must be one of list,tree.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
        $this->sortable = $sortable;
    }


    public function isSortable()
    {
        return (boolean)$this->sortable;
    }


    public function isSortableAsList()
    {
        // if a content type is sortable at all, it's at least sortable as a list
        return $this->isSortable();
    }


    public function isSortableAsTree()
    {
        if ($this->sortable == 'tree')
        {
            return true;
        }

        return false;
    }


    public function setTimeShiftable($timeShiftable)
    {
        $this->timeShiftable = $timeShiftable;
    }


    public function isTimeShiftable()
    {
        return $this->timeShiftable;
    }


    public function addSynchronizedProperty($property, $scope = self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL)
    {

        switch ($scope)
        {
            case self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL:
                if (($key = array_search($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES])) !== false)
                {
                    unset($this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES][$key]);
                }
                if (($key = array_search($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES])) !== false)
                {
                    unset($this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES][$key]);
                }
                $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL][] = $property;
                break;
            case self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES:
                if (!in_array($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]))
                {
                    if (($key = array_search($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES])) !== false)
                    {
                        unset($this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES][$key]);
                        $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL][] = $property;
                    }
                    else
                    {
                        $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES][] = $property;
                    }
                }
                break;
            case self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES:
                if (!in_array($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL]))
                {
                    if (($key = array_search($property, $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES])) !== false)
                    {
                        unset($this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES][$key]);
                        $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL][] = $property;
                    }
                    else
                    {
                        $this->synchronizedProperties[self::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES][] = $property;
                    }
                }
                break;
        }
    }


    public function getSynchronizedProperties()
    {
        return $this->synchronizedProperties;
    }

}