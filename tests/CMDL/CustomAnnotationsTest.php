<?php

namespace CMDL;

use CMDL\Annotations\CustomAnnotation;
use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;
use CMDL\FormElementDefinitions\InsertFormElementDefinition;

class CustomAnnotationsTest extends \PHPUnit_Framework_TestCase
{

    public function testCustomAnnotation()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@custom type p1 p2 p3 p4 (l1) (l2)');

        $this->assertCount(1, $contentTypeDefinition->getCustomAnnotations());

        $customAnnotations = $contentTypeDefinition->getCustomAnnotations();

        /** @var CustomAnnotation $customAnnotation */
        $customAnnotation = $customAnnotations[0];

        $this->assertInstanceOf('CMDL\Annotations\CustomAnnotation', $customAnnotation);

        $this->assertEquals('type', $customAnnotation->getType());

    }


    public function testCustomAnnotationDuplicates()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@custom type p1 p2 p3 p4 (l1) (l2)
        @custom type2 p1 p2 p3 p4 (l1) (l2)
        @custom type p1 p2 p3 p4 (l1) (l2)');

        $this->assertCount(3, $contentTypeDefinition->getCustomAnnotations());

        $customAnnotations = $contentTypeDefinition->getCustomAnnotations();

        /** @var CustomAnnotation $customAnnotation */
        $customAnnotation = $customAnnotations[0];

        $this->assertInstanceOf('CMDL\Annotations\CustomAnnotation', $customAnnotation);

        $this->assertEquals('type', $customAnnotation->getType());

        /** @var CustomAnnotation $customAnnotation */
        $customAnnotation = $customAnnotations[1];

        $this->assertInstanceOf('CMDL\Annotations\CustomAnnotation', $customAnnotation);

        $this->assertEquals('type2', $customAnnotation->getType());

        /** @var CustomAnnotation $customAnnotation */
        $customAnnotation = $customAnnotations[2];

        $this->assertInstanceOf('CMDL\Annotations\CustomAnnotation', $customAnnotation);

        $this->assertEquals('type', $customAnnotation->getType());

    }


    public function testParams()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@custom type p1 p2 p3 p4 (l1) (l2)');

        $this->assertCount(1, $contentTypeDefinition->getCustomAnnotations());

        $customAnnotations = $contentTypeDefinition->getCustomAnnotations();

        /** @var CustomAnnotation $customAnnotation */
        $customAnnotation = $customAnnotations[0];

        $this->assertEquals('p1', $customAnnotation->getParam(1));
        $this->assertEquals('p2', $customAnnotation->getParam(2));
        $this->assertEquals('p3', $customAnnotation->getParam(3));
        $this->assertEquals('p4', $customAnnotation->getParam(4));
        $this->assertEquals(null, $customAnnotation->getParam(5));

    }

}