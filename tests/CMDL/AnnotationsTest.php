<?php

namespace Tests\CMDL;

use CMDL\Parser;
use PHPUnit\Framework\TestCase;

class AnnotationsTest extends TestCase
{
    public function testContentTypeDefinition()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-02.cmdl', 'test-02', 'News');

        $this->assertEquals('test-02', $contentTypeDefinition->getName());
        $this->assertEquals('News', $contentTypeDefinition->getTitle());

        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-03.cmdl', 'test-03');

        $this->assertEquals('test-03', $contentTypeDefinition->getName());
        $this->assertEquals('News', $contentTypeDefinition->getTitle());
        $this->assertEquals('Example Content Type', $contentTypeDefinition->getDescription());
    }

    public function test1MissingMandatoryParameters()
    {
        Parser::parseCMDLString('@title Test');

        $this->expectException('CMDL\CMDLParserException');

        Parser::parseCMDLString('@title');
    }

    public function test2MissingMandatoryParameters()
    {
        $this->expectException('CMDL\CMDLParserException');

        Parser::parseCMDLString('@description');
    }

    public function testDefaultValueAndHelpHintInfoPlaceholderAnnotations()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-07.cmdl');

        $formElement = $contentTypeDefinition->getViewDefinition('default')->getFormElementDefinition('name');

        $this->assertEquals('New Article', $formElement->getDefaultValue());
        $this->assertEquals('New Article', $formElement->getPlaceholder());
        $this->assertEquals('Remember to choose a SEO friendly title!', $formElement->getHelp());
        $this->assertEquals('Name of the new article', $formElement->getHint());

        //@todo: Validation fails caused by brackets
        //$this->assertEquals('This name is used within all channels (Desktop, Mobile, Podcast)',$formElement->getInfo());
    }

    public function testLanguagesStatusSubtypesAnnotations()
    {
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertEquals(false, $contentTypeDefinition->hasStatusList());

        $contentTypeDefinition = Parser::parseCMDLString('@status none');
        $this->assertEquals(false, $contentTypeDefinition->hasStatusList());

        $contentTypeDefinition = Parser::parseCMDLString('@status (online,offline)');
        $this->assertEquals(true, $contentTypeDefinition->hasStatusList());
        $this->assertContains('online', $contentTypeDefinition->getStatusList());
        $this->assertContains('offline', $contentTypeDefinition->getStatusList());

        $contentTypeDefinition = Parser::parseCMDLString('@status (1:online,2:offline)');
        $this->assertEquals(true, $contentTypeDefinition->hasStatusList());
        $this->assertArrayHasKey('1', $contentTypeDefinition->getStatusList());
        $this->assertArrayHasKey('2', $contentTypeDefinition->getStatusList());

        $contentTypeDefinition = Parser::parseCMDLString('@subtypes none');
        $this->assertEquals(false, $contentTypeDefinition->hasSubtypes());

        $contentTypeDefinition = Parser::parseCMDLString('@subtypes (standard,news,imprint)');
        $this->assertEquals(true, $contentTypeDefinition->hasSubtypes());

        $contentTypeDefinition = Parser::parseCMDLString('@languages none');
        $this->assertEquals(false, $contentTypeDefinition->hasLanguages());

        $contentTypeDefinition = Parser::parseCMDLString('@languages (de:Deutsch,en:English)');
        $this->assertEquals(true, $contentTypeDefinition->hasLanguages());
    }

    public function testWorkspacesAnnotation()
    {
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertArrayHasKey('default', $contentTypeDefinition->getWorkspaces());

        $contentTypeDefinition = Parser::parseCMDLString('@workspaces (draft,live)');
        $this->assertArrayHasKey('draft', $contentTypeDefinition->getWorkspaces());
    }

    public function testSortableAnnotation()
    {
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertFalse($contentTypeDefinition->isSortable());
        $this->assertFalse($contentTypeDefinition->isSortableAsList());
        $this->assertFalse($contentTypeDefinition->isSortableAsTree());

        $contentTypeDefinition = Parser::parseCMDLString('@sortable ');
        $this->assertTrue($contentTypeDefinition->isSortable());
        $this->assertTrue($contentTypeDefinition->isSortableAsList());
        $this->assertFalse($contentTypeDefinition->isSortableAsTree());

        $contentTypeDefinition = Parser::parseCMDLString('@sortable tree');
        $this->assertTrue($contentTypeDefinition->isSortable());
        $this->assertTrue($contentTypeDefinition->isSortableAsList());
        $this->assertTrue($contentTypeDefinition->isSortableAsTree());
    }

    public function testTimeShiftableAnnotation()
    {
        $contentTypeDefinition = Parser::parseCMDLString('');
        $this->assertFalse($contentTypeDefinition->isTimeShiftAble());

        $contentTypeDefinition = Parser::parseCMDLString('@time-shiftable ');
        $this->assertTrue($contentTypeDefinition->isTimeShiftAble());
    }

    public function testHiddenPropertiesAnnotation()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-07.cmdl');
        $this->assertTrue($contentTypeDefinition->hasProperty('name'));
        $this->assertTrue($contentTypeDefinition->hasProperty('status'));
        $this->assertTrue($contentTypeDefinition->hasProperty('licence'));
        $this->assertTrue($contentTypeDefinition->hasProperty('source'));

        $view = $contentTypeDefinition->getViewDefinition('default');
        $this->assertTrue($view->hasProperty('name'));
        $this->assertTrue($view->hasProperty('status'));
        $this->assertTrue($view->hasProperty('licence'));
        $this->assertTrue($view->hasProperty('source'));

        $view = $contentTypeDefinition->getViewDefinition('list');
        $this->assertTrue($view->hasProperty('name'));
        $this->assertTrue($view->hasProperty('status'));
        $this->assertFalse($view->hasProperty('licence'));
        $this->assertTrue($view->hasProperty('source'));
    }
}
