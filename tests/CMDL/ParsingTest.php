<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ClippingDefinition;
use CMDL\FormElementDefinition;

class ParsingTest extends \PHPUnit_Framework_TestCase
{

    public function testFileNotFoundException()
    {
        $this->setExpectedException('CMDL\CMDLParserException');
        Parser::parseCMDLFile('tests/input/test-00.cmdl');
    }


    public function testFileFound()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $s = $contentTypeDefinition->getCMDL();

        $this->assertStringEqualsFile('tests/input/test-01.cmdl', $s);
        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);

    }


    public function testClippingNotDefined()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $this->setExpectedException('CMDL\CMDLParserException');

        $contentTypeDefinition->getClippingDefinition('none');

    }


    public function testClippingsDefined()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $viewDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $viewDefinition);
        $viewDefinition = $contentTypeDefinition->getClippingDefinition('insert');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $viewDefinition);
        $viewDefinition = $contentTypeDefinition->getClippingDefinition('edit');
        $this->assertInstanceOf('CMDL\ClippingDefinition', $viewDefinition);

    }


    public function testFieldsReturned()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        /* @var FormElementDefinition */
        $fieldDefinition = $clippingDefinition->getFormElementDefinition('name');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fieldDefinition);
    }


    public function testHeadlinesExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        $fields = $clippingDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $fields[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $fields[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[4]);

    }


    public function testSectionsExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-03.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');

        $fields = $clippingDefinition->getFormElementDefinitions();

        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $fields[0]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $fields[1]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[2]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[3]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $fields[4]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionStartFormElementDefinition', $fields[5]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\HeadlineFormElementDefinition', $fields[6]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[7]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\SectionEndFormElementDefinition', $fields[8]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[9]);
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[9]);

    }


    public function testInsertionsExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-04.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $fields         = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('edit');
        $fields         = $clippingDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var InsertionDefinition */
        $insertionDefinition = $contentTypeDefinition->getInsertionDefinition('insert1');
        $fields         = $insertionDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var InsertionDefinition */
        $insertionDefinition = $contentTypeDefinition->getInsertionDefinition('insert2');
        $fields         = $insertionDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);
    }

}