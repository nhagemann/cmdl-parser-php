<?php

namespace CMDL\Annotations;

use CMDL\Annotation;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;

class DataTypeSortableAnnotation extends Annotation
{
    protected $annotationType = 'sortable';

    public function apply()
    {
        assert($this->dataTypeDefinition instanceof ContentTypeDefinition);

        if ($this->hasParam(1)) {
            switch ($this->getParam(1)) {
                case 'not':
                    $this->dataTypeDefinition->setSortable(false);
                    break;
                case 'list':
                case 'tree':
                    $this->dataTypeDefinition->setSortable($this->getParam(1));
                    break;
                default:
                    throw  new CMDLParserException(
                        'Parameter "type" of annotation ' . $this->annotationType . ' must be one of not, list, tree.',
                        CMDLParserException::CMDL_INVALID_OPTION_VALUE
                    );
            }
        } else {
            $this->dataTypeDefinition->setSortable('list');
        }

        return $this->dataTypeDefinition;
    }
}
