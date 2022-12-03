<?php

namespace CMDL\FormElementDefinitions;

use CMDL\FormElementDefinition;

class RemoteReferenceFormElementDefinition extends ReferenceFormElementDefinition
{
    protected $type = 'dropdown';

    protected $elementType = 'remote-reference';

    protected $contentType = null;

    protected $workspace = 'default';

    protected $order = 'name';

    protected $url = null;

    protected $language = 'default';

    protected $timeshift = 0;


    public function __construct($name = null, $params = [], $lists = [])
    {
        if (isset($params[0])) {
            $this->setUrl($params[0]);
        }

        if (isset($params[1])) {
            $this->setContentType($params[1]);
        }

        if (isset($params[2])) {
            $this->setType($params[2]);
        }

        if (isset($params[3])) {
            $this->setWorkspace($params[3]);
        }

        if (isset($params[4])) {
            $this->setOrder($params[4]);
        }

        if (isset($params[5])) {
            $this->setLanguage($params[5]);
        }

        if (isset($params[6])) {
            $this->setTimeshift($params[6]);
        }

        // skip constructor of form element reference, since it has less parameters and therefore a slightly different parameter order
        FormElementDefinition::__construct($name, $params, $lists);
    }


    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function getUrl()
    {
        return $this->url;
    }
}
