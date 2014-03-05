<?php

namespace CMDL;

use CMDL\Parser;

class SequenceWithinInsertTest extends \PHPUnit_Framework_TestCase
{

    public function testFormElementFound()
    {
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-11.cmdl');

        $this->assertInstanceOf('CMDL\ContentTypeDefinition', $contentTypeDefinition);

        $this->assertTrue($contentTypeDefinition->hasProperty('name'));
        $this->assertTrue($contentTypeDefinition->hasProperty('comment'));
        $this->assertTrue($contentTypeDefinition->hasProperty('a'));
        $this->assertTrue($contentTypeDefinition->hasProperty('b'));


        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $this->assertTrue($clippingDefinition->hasProperty('name'));
        $this->assertTrue($clippingDefinition->hasProperty('comment'));
        $this->assertTrue($clippingDefinition->hasProperty('a'));
        $this->assertTrue($clippingDefinition->hasProperty('b'));


        try {
            $clippingDefinition->getFormElementDefinition('name');
            $clippingDefinition->getFormElementDefinition('comment');
            $clippingDefinition->getFormElementDefinition('a');
            $clippingDefinition->getFormElementDefinition('b');

            $this->addToAssertionCount(4);
        }
        catch (\Exception $e) {
            $this->fail($e->getMessage());
        }



    }

}