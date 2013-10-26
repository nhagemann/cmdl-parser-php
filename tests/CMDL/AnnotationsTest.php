<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ClippingDefinition;
use CMDL\FormElementDefinition;

class AnnotationsTest extends \PHPUnit_Framework_TestCase
{

    public function testContentTypeDefinition()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl', 'test-02', 'News');

        $this->assertEquals('test-02', $contentTypeDefinition->getName());
        $this->assertEquals('News', $contentTypeDefinition->getTitle());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-03.cmdl', 'test-03');

        $this->assertEquals('test-03', $contentTypeDefinition->getName());
        $this->assertEquals('News', $contentTypeDefinition->getTitle());
        $this->assertEquals('Example Content Type', $contentTypeDefinition->getDescription());

    }


    public function test1MissingMandatoryParameters()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@content-type-title Test');

        $this->setExpectedException('CMDL\CMDLParserException');
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@content-type-title');

    }


    public function test2MissingMandatoryParameters()
    {

        $this->setExpectedException('CMDL\CMDLParserException');
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@content-type-description');
    }


    public function testDefaultValueAndHelpHintInfoPlaceholderAnnotations()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-07.cmdl');

        $formElement = $contentTypeDefinition->getClippingDefinition('default')->getFormElementDefinition('name');

        $this->assertEquals('New Article', $formElement->getDefaultValue());
        $this->assertEquals('New Article', $formElement->getPlaceholder());
        $this->assertEquals('Remember to choose a SEO friendly title!', $formElement->getHelp());
        $this->assertEquals('Name of the new article', $formElement->getHint());

        //@todo: Validation fails caused by brackets
        //$this->assertEquals('This name is used within all channels (Desktop, Mobile, Podcast)',$formElement->getInfo());
    }


    public function testLanguagesStatusSubtypesAnnotations()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertEquals(false, $contentTypeDefinition->hasStatusList());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@status none');
        $this->assertEquals(false, $contentTypeDefinition->hasStatusList());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@status (online,offline)');
        $this->assertEquals(true, $contentTypeDefinition->hasStatusList());
        $this->assertContains('online', $contentTypeDefinition->getStatusList());
        $this->assertContains('offline', $contentTypeDefinition->getStatusList());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@status (1:online,2:offline)');
        $this->assertEquals(true, $contentTypeDefinition->hasStatusList());
        $this->assertArrayHasKey('1', $contentTypeDefinition->getStatusList());
        $this->assertArrayHasKey('2', $contentTypeDefinition->getStatusList());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@subtypes none');
        $this->assertEquals(false, $contentTypeDefinition->hasSubtypes());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@subtypes (standard,news,imprint)');
        $this->assertEquals(true, $contentTypeDefinition->hasSubtypes());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@languages none');
        $this->assertEquals(false, $contentTypeDefinition->hasLanguages());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@languages (de:Deutsch,en:English)');
        $this->assertEquals(true, $contentTypeDefinition->hasLanguages());

    }


    public function testWorkspacesAnnotation()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertArrayHasKey('default', $contentTypeDefinition->getWorkspaces());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@workspaces (draft,live)');
        $this->assertArrayHasKey('draft', $contentTypeDefinition->getWorkspaces());
    }


    public function testOperationsAnnotation()
    {
        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertTrue($contentTypeDefinition->hasListOperation());
        $this->assertTrue($contentTypeDefinition->hasGetOperation());
        $this->assertTrue($contentTypeDefinition->hasInsertOperation());
        $this->assertTrue($contentTypeDefinition->hasUpdateOperation());
        $this->assertTrue($contentTypeDefinition->hasDeleteOperation());
        $this->assertTrue($contentTypeDefinition->hasSortOperation());
        $this->assertTrue($contentTypeDefinition->hasTimeshiftOperation());
        $this->assertTrue($contentTypeDefinition->hasRevisionsOperation());


        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLString('@operations (list)');
        $this->assertTrue($contentTypeDefinition->hasListOperation());
        $this->assertFalse($contentTypeDefinition->hasGetOperation());
        $this->assertFalse($contentTypeDefinition->hasInsertOperation());
        $this->assertFalse($contentTypeDefinition->hasUpdateOperation());
        $this->assertFalse($contentTypeDefinition->hasDeleteOperation());
        $this->assertFalse($contentTypeDefinition->hasSortOperation());
        $this->assertFalse($contentTypeDefinition->hasTimeshiftOperation());
        $this->assertFalse($contentTypeDefinition->hasRevisionsOperation());

    }
}