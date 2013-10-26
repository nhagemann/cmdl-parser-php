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

        $this->assertEquals('New Article',$formElement->getDefaultValue());
        $this->assertEquals('New Article',$formElement->getPlaceholder());
        $this->assertEquals('Remember to choose a SEO friendly title!',$formElement->getHelp());
        $this->assertEquals('Name of the new article',$formElement->getHint());

        //@todo: Validation fails caused by brackets
        //$this->assertEquals('This name is used within all channels (Desktop, Mobile, Podcast)',$formElement->getInfo());
    }
}