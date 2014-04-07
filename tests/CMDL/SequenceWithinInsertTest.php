<?php

namespace CMDL;

use CMDL\Parser;

class SequenceWithinInsertTest extends \PHPUnit_Framework_TestCase
{

    public function testFormElementFound()
    {
        return;

        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-11.cmdl');

        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);

        $this->assertTrue($contentTypeDefinition->hasProperty('name'));
        $this->assertTrue($contentTypeDefinition->hasProperty('comment'));
        $this->assertTrue($contentTypeDefinition->hasProperty('a'));
        $this->assertTrue($contentTypeDefinition->hasProperty('b'));

        /* @var ViewDefinition */
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $this->assertTrue($viewDefinition->hasProperty('name'));
        $this->assertTrue($viewDefinition->hasProperty('comment'));
        $this->assertTrue($viewDefinition->hasProperty('a'));
        $this->assertTrue($viewDefinition->hasProperty('b'));

        try
        {
            $viewDefinition->getFormElementDefinition('name');
            $viewDefinition->getFormElementDefinition('comment');
            $viewDefinition->getFormElementDefinition('a');
            $viewDefinition->getFormElementDefinition('b');

            $this->addToAssertionCount(4);
        }
        catch (\Exception $e)
        {
            $this->fail($e->getMessage());
        }

    }

}