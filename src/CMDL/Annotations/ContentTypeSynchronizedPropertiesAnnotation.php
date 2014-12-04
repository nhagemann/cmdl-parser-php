<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;

class ContentTypeSynchronizedPropertiesAnnotation extends Annotation
{

    protected $annotationType = 'synchronized-properties';


    public function apply()
    {

        if (!$this->hasList(1))
        {
            throw new CMDLParserException('Missing mandatory properties list for annotation @synchronized-properties.', CMDLParserException::CMDL_MISSING_MANDATORY_PARAM);
        }

        $scope = ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL;

        if ($this->hasParam(1))
        {
            $scope = $this->getParam(1);
            if (!in_array($scope, array(ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_GLOBAL, ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_LANGUAGES, ContentTypeDefinition::SCOPE_SYNCHRONIZED_PROPERTY_WORKSPACES)))
            {
                throw new CMDLParserException('Invalid parameter value ' . $this->getParam(1) . ' for annotation @synchronized-properties.', CMDLParserException::CMDL_INVALID_OPTION_VALUE);
            }
        }





        $list = $this->getList(1);

        foreach ($list as $property)
        {
            if ($this->dataTypeDefinition->hasProperty($property))
            {
                $this->dataTypeDefinition->addSynchronizedProperty($property,$scope);
            }
            else
            {
                throw new CMDLParserException('Unknown property ' . $property . ' within annotation @synchronized-properties.', CMDLParserException::CMDL_UNKNOWN_PROPERTY);
            }
        }

        return $this->dataTypeDefinition;
    }

}
