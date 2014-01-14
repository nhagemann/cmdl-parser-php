<?php

namespace CMDL;

use CMDL\Parser;
use CMDL\CMDLParserException;
use CMDL\ContentTypeDefinition;
use CMDL\ClippingDefinition;
use CMDL\FormElementDefinition;

class ClippingConventionsTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaults()
    {

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-04.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getClippingDefinition('default');
        $this->assertEquals('default', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getEditClippingDefinition();
        $this->assertEquals('edit', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getInsertClippingDefinition();
        $this->assertEquals('default', $clippingDefinition->getName());

        /* @var ContentTypeDefinition */
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-10.cmdl');

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getInsertClippingDefinition();
        $this->assertEquals('insert', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getInsertClippingDefinition('insert');
        $this->assertEquals('insert', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getInsertClippingDefinition('megainsert');
        $this->assertEquals('insert', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getExchangeClippingDefinition();
        $this->assertEquals('exchange', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getExchangeClippingDefinition('exchange');
        $this->assertEquals('exchange', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getExchangeClippingDefinition('megaexchange');
        $this->assertEquals('megaexchange', $clippingDefinition->getName());

        /* @var ClippingDefinition */
        $clippingDefinition = $contentTypeDefinition->getListClippingDefinition();
        $this->assertEquals('list', $clippingDefinition->getName());
    }

}