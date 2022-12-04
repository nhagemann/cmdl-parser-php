<?php

namespace CMDL;

abstract class ContentTypeAnnotation extends Annotation
{
    /**
     * @var ContentTypeDefinition|null
     */
    protected $dataTypeDefinition = null;
}
