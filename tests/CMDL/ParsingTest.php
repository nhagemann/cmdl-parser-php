<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ViewDefinition;
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


    public function testViewNotDefined()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        $this->setExpectedException('CMDL\CMDLParserException');

        $contentTypeDefinition->getViewDefinition('none');

    }


    public function testViewsDefined()
    {
        /* @var ContentTypeDefinition */
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
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-01.cmdl');

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        /* @var FormElementDefinition */
        $fieldDefinition = $viewDefinition->getFormElementDefinition('name');
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fieldDefinition);
    }


    public function testHeadlinesExtracted()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl');

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $fields = $viewDefinition->getFormElementDefinitions();

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

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');

        $fields = $viewDefinition->getFormElementDefinitions();

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

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $fields         = $viewDefinition->getFormElementDefinitions();
        $this->assertInstanceOf('CMDL\FormElementDefinitions\TextfieldFormElementDefinition', $fields[0]);

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('edit');
        $fields         = $viewDefinition->getFormElementDefinitions();
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