<?php

namespace Tests\CMDL;

use CMDL\CMDLParserException;
use CMDL\Parser;
use PHPUnit\Framework\TestCase;

class SequenceWithinInsertTest extends TestCase
{
    public function testSequenceWithinInsertNotFullyImplemented()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-11.cmdl');

        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);

        $this->assertTrue($contentTypeDefinition->hasProperty('name'));
        $this->assertTrue($contentTypeDefinition->hasProperty('comment'));
        $this->assertTrue($contentTypeDefinition->hasProperty('a'));
        $this->assertTrue($contentTypeDefinition->hasProperty('b'));

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $this->assertTrue($viewDefinition->hasProperty('name'));
        $this->assertTrue($viewDefinition->hasProperty('comment'));
        $this->assertTrue($viewDefinition->hasProperty('a'));
        $this->assertTrue($viewDefinition->hasProperty('b'));

        $viewDefinition->getFormElementDefinition('name');
        $viewDefinition->getFormElementDefinition('comment');
        $viewDefinition->getFormElementDefinition('a');

        // Property b has been created, but form element for property b cannot be retrieved
        $this->expectException(CMDLParserException::class);
        $viewDefinition->getFormElementDefinition('b');
    }
}
