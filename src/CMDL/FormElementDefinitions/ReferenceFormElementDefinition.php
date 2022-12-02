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
    }//end __construct()


    public function setType($type)
    {
        if (in_array($type, [ 'dropdown', 'radio', 'toggle' ])) {
            $this->type = $type;
        } else {
            throw  new CMDLParserException('Parameter "type" of form element ' . $this->elementType . ' must be one of dropdown, radio, toggle.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
        }
    }//end setType()


    public function getType()
    {
        return $this->type;
    }//end getType()


    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }//end setContentType()


    public function getContentType()
    {
        return $this->contentType;
    }//end getContentType()


    /**
     * @return null
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }//end getRepositoryName()


    /**
     * @param null $repositoryName
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;
    }//end setRepositoryName()


    public function hasRepositoryName()
    {
        return (bool) $this->repositoryName;
    }//end hasRepositoryName()


    public function setOrder($order)
    {
        $this->order = $order;
    }//end setOrder()


    public function getOrder()
    {
        return $this->order;
    }//end getOrder()


    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
    }//end setWorkspace()


    public function getWorkspace()
    {
        return $this->workspace;
    }//end getWorkspace()


    public function setTimeshift($timeshift)
    {
        $this->timeshift = $timeshift;
    }//end setTimeshift()


    public function getTimeshift()
    {
        return $this->timeshift;
    }//end getTimeshift()


    public function setLanguage($language)
    {
        $this->language = $language;
    }//end setLanguage()


    public function getLanguage()
    {
        return $this->language;
    }//end getLanguage()
}//end class
