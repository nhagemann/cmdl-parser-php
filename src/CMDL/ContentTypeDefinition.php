<?php

namespace CMDL;

class ContentTypeDefinition extends DataTypeDefinition
{
    protected $subtypes = null;

    protected $statusList = null;

    protected $sortable = null;

    protected $namingPattern = false;

    public function hasStatusList()
    {
        if ($this->statusList === null || count($this->statusList) == 0) {
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
        if ($this->subtypes === null || count($this->subtypes) == 0) {
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

    public function setSortable($sortable)
    {
        if (!in_array($sortable, ['list', 'tree'])) {
            throw new CMDLParserException(
                'Invalid sortables setting ("' . $sortable . '") for content type ' . rtrim(' ' . $this->getName()) . '. Must be one of list,tree.',
                CMDLParserException::CMDL_INVALID_OPTION_VALUE
            );
        }

        $this->sortable = $sortable;
    }

    public function isSortable()
    {
        return (bool) $this->sortable;
    }

    public function isSortableAsList()
    {
        // if a content type is sortable at all, it's at least sortable as a list
        return $this->isSortable();
    }

    public function isSortableAsTree()
    {
        if ($this->sortable == 'tree') {
            return true;
        }

        return false;
    }

    public function hasNamingPattern()
    {
        return (bool) $this->namingPattern;
    }

    public function getNamingPattern()
    {
        return $this->namingPattern;
    }

    public function setNamingPattern($namingPattern)
    {
        $this->namingPattern = $namingPattern;
    }
}
