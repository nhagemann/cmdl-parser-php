<?php

namespace Tests\CMDL;

use CMDL\Parser;
use PHPUnit\Framework\TestCase;

class ViewConventionsTest extends TestCase
{
    public function testDefaults()
    {
        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-04.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getViewDefinition('default');
        $this->assertEquals('default', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getEditViewDefinition();
        $this->assertEquals('edit', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getInsertViewDefinition();
        $this->assertEquals('default', $viewDefinition->getName());

        // @var ContentTypeDefinition
        $contentTypeDefinition = Parser::parseCMDLFile('tests/input/test-10.cmdl');

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getInsertViewDefinition();
        $this->assertEquals('insert', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getInsertViewDefinition('insert');
        $this->assertEquals('insert', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getInsertViewDefinition('megainsert');
        $this->assertEquals('insert', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getExchangeViewDefinition();
        $this->assertEquals('exchange', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getExchangeViewDefinition('exchange');
        $this->assertEquals('exchange', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getExchangeViewDefinition('megaexchange');
        $this->assertEquals('megaexchange', $viewDefinition->getName());

        // @var ViewDefinition
        $viewDefinition = $contentTypeDefinition->getListViewDefinition();
        $this->assertEquals('list', $viewDefinition->getName());
    }
}
