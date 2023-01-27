<?php

namespace Tests\CMDL;

use CMDL\Parser;
use PHPUnit\Framework\TestCase;

class PropertiesTest extends TestCase
{
    public function testUnknownView()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $this->expectException('CMDL\CMDLParserException');
        $contentTypeDefinition->getProperties('unknownview');
    }

    public function testAllProperties()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $properties = $contentTypeDefinition->getProperties('default');
        $this->assertCount(5, $properties);

        $properties = $contentTypeDefinition->getProperties('edit');
        $this->assertCount(9, $properties);

        $properties = $contentTypeDefinition->getProperties('insert');
        $this->assertCount(6, $properties);

        $properties = $contentTypeDefinition->getProperties();
        $this->assertCount(9, $properties);
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

    public function testProtectedProperties()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-05.cmdl');

        $properties = $contentTypeDefinition->getProtectedProperties('default');
        $this->assertCount(0, $properties);

        $properties = $contentTypeDefinition->getProtectedProperties('edit');
        $this->assertCount(1, $properties);

        $properties = $contentTypeDefinition->getProtectedProperties('insert');
        $this->assertCount(0, $properties);
    }
}
