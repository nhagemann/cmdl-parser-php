<?php

namespace CMDL;

use CMDL\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testIdentifierGeneration()
    {
        $this->assertEquals('koenig', Util::generateValidIdentifier('König'));

        $this->assertEquals('', Util::generateValidIdentifier('ฉันไม่สามารถอ่านนี้'));
    }//end testIdentifierGeneration()


    public function testParamExtraction()
    {
        $result = Util::extractParams('param1 "param 2" param3 (a,b,c)');

        $this->assertCount(3, $result);
        $this->assertEquals('param1', $result[0]);
        $this->assertEquals('param 2', $result[1]);
        $this->assertEquals('param3', $result[2]);

        // params cannot contain quotation marks, so a string like the following will break the extraction deterministic
        $result = Util::extractParams('"That is a not working "quote""');
        $this->assertCount(3, $result);
    }//end testParamExtraction()


    public function testListExtraction()
    {

        $result = Util::extractLists('(1:online,2:offline)');
        $this->assertCount(1, $result);
        $this->assertCount(2, $result[0]);

        $result = Util::extractLists('param1 "param 2" param3 (a,b,c)');
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('a', $result[0]);
        $this->assertEquals('a', $result[0]['a']);

        $result = Util::extractLists('(a:dog,horse,c:cow) (green, blue, red ,purple)');

        $this->assertCount(2, $result);

        $this->assertArrayHasKey('a', $result[0]);
        $this->assertArrayHasKey('horse', $result[0]);
        $this->assertArrayHasKey('c', $result[0]);

        $this->assertContains('green', $result[1]);
        $this->assertContains('blue', $result[1]);
        $this->assertContains('red', $result[1]);
        $this->assertContains('purple', $result[1]);

        $result = Util::extractLists('(a:"Lassy",\'Black Beauty \',c: Milka Cow )');
        $this->assertCount(1, $result);
        $this->assertContains('Lassy', $result[0]);
        $this->assertContains('Black Beauty ', $result[0]);
        $this->assertContains('Milka Cow', $result[0]);
    }//end testListExtraction()
}//end class
