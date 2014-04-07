<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
use CMDL\FormElementDefinition;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{

    public function testUnknownView()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $this->setExpectedException('CMDL\CMDLParserException');
        $contentTypeDefinition->getProperties('unknownview');
    }


    public function testAllProperties()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $properties = $contentTypeDefinition->getProperties('default');
        $this->assertCount(3, $properties);

        $properties = $contentTypeDefinition->getProperties('edit');
        $this->assertCount(7, $properties);

        $properties = $contentTypeDefinition->getProperties('insert');
        $this->assertCount(4, $properties);

        $properties = $contentTypeDefinition->getProperties();
        $this->assertCount(7, $properties);

    }


    public function testMandatoryProperties()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $properties = $contentTypeDefinition->getMandatoryProperties('default');
        $this->assertCount(0, $properties);

        $properties = $contentTypeDefinition->getMandatoryProperties('edit');
        $this->assertCount(3, $properties);

        $properties = $contentTypeDefinition->getMandatoryProperties('insert');
        $this->assertCount(1, $properties);

    }


    public function testUniqueProperties()
    {

        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $properties = $contentTypeDefinition->getUniqueProperties('default');
        $this->assertCount(0, $properties);

        $properties = $contentTypeDefinition->getUniqueProperties('edit');
        $this->assertCount(1, $properties);

        $properties = $contentTypeDefinition->getUniqueProperties('insert');
        $this->assertCount(1, $properties);

    }
}