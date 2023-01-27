<?php

namespace Tests\CMDL;

use CMDL\Parser;
use PHPUnit\Framework\TestCase;

class ParsingTest extends TestCase
{
    public function testFileNotFoundException()
    {
        $this->expectException('CMDL\CMDLParserException');
        Parser::parseCMDLFile('tests/input/test-00.cmdl');
    }

    public function testFileFound()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $s = $contentTypeDefinition->getCMDL();

        $this->assertStringEqualsFile('tests/input/test-01.cmdl', $s);
        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);
    }

    public function testViewNotDefined()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $this->expectException('CMDL\CMDLParserException');

        $contentTypeDefinition->getViewDefinition('none');
    }

    public function testViewsDefined()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $this->assertInstanceOf('CMDL\ViewDefinition', $viewDefinition);
        $viewDefinition = $contentTypeDefinition->getViewDefinition('insert');
        $this->assertInstanceOf('CMDL\ViewDefinition', $viewDefinition);
        $viewDefinition = $contentTypeDefinition->getViewDefinition('edit');
        $this->assertInstanceOf('CMDL\ViewDefinition', $viewDefinition);
    }

    public function testFieldsReturned()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        // @var FormElementDefinition
        $formElementDefinition = $viewDefinition->getFormElementDefinition('name');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElementDefinition);
    }

    public function testHeadlinesExtracted()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[4]);
    }

    public function testSectionsExtracted()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-03.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[6]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[7]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[8]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[9]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[9]);
    }

    public function testClippingsExtracted()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-04.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $fields         = $viewDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('edit');
        $fields         = $viewDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        // @var ClippingDefinition
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('insert1');
        $fields = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        // @var ClippingDefinition
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('insert2');
        $fields = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);
    }

    public function testTabsExtracted()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-06.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $formElements[6]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $formElements[7]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[8]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $formElements[9]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[10]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabNextFormElementDefinition', $formElements[11]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[12]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[13]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabEndFormElementDefinition', $formElements[14]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[15]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[16]);
    }

    public function testTabsNotClosedExtracted()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-12.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $formElements = $viewDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabNextFormElementDefinition', $formElements[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabEndFormElementDefinition', $formElements[6]);

        $this->assertTrue($contentTypeDefinition->hasClippingDefinition('clipping'));

        $formElements = $contentTypeDefinition->getClippingDefinition('clipping')->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabStartFormElementDefinition', $formElements[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $formElements[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TabEndFormElementDefinition', $formElements[3]);
    }
}
