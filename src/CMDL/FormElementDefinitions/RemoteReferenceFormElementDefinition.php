<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;
use CMDL\CMDLParserException;

class RemoteReferenceFormElementDefinition extends FormElementDefinition
{

    protected $type = 'dropdown';

    protected $elementType = 'remote-reference';

    protected $contentType = null;

    protected $workspace = 'default';

    protected $order = 'name';

    protected $url = null;

    protected $language = 'default';

    protected $timeshift = 0;


    public function __construct($name, $params = array(), $lists = array())
    {

        if (isset($params[0]))
        {
            $this->setUrl($params[0]);
        }
        if (isset($params[1]))
        {
            $this->setContentType($params[1]);
        }
        if (isset($params[2]))
        {
            $this->setType($params[2]);
        }
        if (isset($params[3]))
        {
            $this->setWorkspace($params[3]);
        }
        if (isset($params[4]))
        {
            $this->setOrder($params[4]);
        }
        if (isset($params[5]))
        {
            $this->setLanguage($params[5]);
        }
        if (isset($params[6]))
        {
            $this->setTimeshift($params[6]);
        }

        parent::__construct($name, $params, $lists);
    }


    public function setType($type)
    {
        if (in_array($type, array( 'dropdown', 'radio', 'toggle' )))
        {
            $this->type = $type;
        }
        else
        {
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


    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function getUrl()
    {
        return $this->url;
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
