<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class ReferenceFormElementDefinition extends FormElementDefinition
{
    protected $type = 'dropdown';

    protected $elementType = 'reference';

    protected $repositoryName = null;

    protected $contentType = null;

    protected $workspace = 'default';

    protected $order = 'name';

    protected $language = 'default';

    protected $timeshift = 0;


    public function __construct($name, $params = [], $lists = [])
    {

        if (!isset($params[0])) {
            throw new CMDLParserException('Missing mandatory parameter contenttype for form element reference.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $contentTypeName = $params[0];

        if (strpos($contentTypeName, '.') !== false) {
            $split = explode('.', $contentTypeName);
            $this->setRepositoryName($split[0]);
            $contentTypeName = $split[1];
        }

        $this->setContentType($contentTypeName);

        if (isset($params[1])) {
            $this->setType($params[1]);
        }

        if (isset($params[2])) {
            $this->setWorkspace($params[2]);
        }

        if (isset($params[3])) {
            $this->setOrder($params[3]);
        }

        if (isset($params[4])) {
            $this->setLanguage($params[4]);
        }

        if (isset($params[5])) {
            $this->setTimeshift($params[5]);
        }

        parent::__construct($name, $params, $lists);
    }


    public function setType($type)
    {
        if (in_array($type, [ 'dropdown', 'radio', 'toggle' ])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of dropdown, radio, toggle.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }


    public function getType()
    {
        return $this->type;
    }


    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    public function getContentType()
    {
        return $this->contentType;
    }


    /**
     * @return null
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }


    /**
     * @param null $repositoryName
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;
    }


    public function hasRepositoryName()
    {
        return (bool) $this->repositoryName;
    }


    public function setOrder($order)
    {
        $this->order = $order;
    }


    public function getOrder()
    {
        return $this->order;
    }


    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }


    public function getWorkspace()
    {
        return $this->workspace;
    }


    public function setTimeshift($timeshift)
    {
        $this->timeshift = $timeshift;
    }


    public function getTimeshift()
    {
        return $this->timeshift;
    }


    public function setLanguage($language)
    {
        $this->language = $language;
    }


    public function getLanguage()
    {
        return $this->language;
    }
}
