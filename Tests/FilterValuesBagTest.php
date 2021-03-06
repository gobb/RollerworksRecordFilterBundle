<?php

/**
 * This file is part of the RollerworksRecordFilterBundle.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\RecordFilterBundle\Tests;

use Rollerworks\Bundle\RecordFilterBundle\Value\FilterValuesBag;
use Rollerworks\Bundle\RecordFilterBundle\Value\Compare;
use Rollerworks\Bundle\RecordFilterBundle\Value\Range;
use Rollerworks\Bundle\RecordFilterBundle\Value\SingleValue;

class FilterValuesBagTest extends \PHPUnit_Framework_TestCase
{
    public function testLabel()
    {
        $struct = new FilterValuesBag('test', 'none');

        $this->assertEquals('test', $struct->getLabel());
    }

    public function testInput()
    {
        $struct = new FilterValuesBag('test', 'none');

        $this->assertEquals('none', $struct->getOriginalInput());
    }

    public function testLooseValues()
    {
        $struct = new FilterValuesBag('test', 'none', array(new SingleValue('4')));

        $this->assertTrue($struct->hasSingleValues());
        $this->assertEquals(array(new SingleValue('4')), $struct->getSingleValues());
    }

    public function testExcludes()
    {
        $struct = new FilterValuesBag('test', 'none', array(), array(new SingleValue('4')));

        $this->assertTrue($struct->hasExcludes());
        $this->assertEquals(array(new SingleValue('4')), $struct->getExcludes());
    }

    public function testRanges()
    {
        $struct = new FilterValuesBag('test', 'none', array(), array(), array(new Range(10, 100)), array());

        $this->assertTrue($struct->hasRanges());
        $this->assertEquals(array(new Range(10, 100)), $struct->getRanges());
    }

    public function testExcludedRanges()
    {
        $struct = new FilterValuesBag('test', 'none', array(), array(), array(new Range(10, 100)), array(), array(new Range(12, 20)));

        $this->assertTrue($struct->hasRanges());
        $this->assertTrue($struct->hasExcludedRanges());

        $this->assertEquals(array(new Range(10, 100)), $struct->getRanges());
        $this->assertEquals(array(new Range(12, 20)), $struct->getExcludedRanges());
    }

    public function testCompares()
    {
        $struct = new FilterValuesBag('test', 'none', array(), array(), array(), array(new Compare(10, '>')));

        $this->assertTrue($struct->hasCompares());
        $this->assertEquals(array(new Compare(10, '>')), $struct->getCompares());
    }

    public function testUnsetLooseSingleValue()
    {
        $struct = new FilterValuesBag('test', 'none', array(0 => new SingleValue('4'), 4 => new SingleValue('4')), array(1 => new SingleValue('10'), 5 => new SingleValue('20')), array(2 => new Range(10, 100), 6 => new Range(110, 200)), array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')));

        $this->assertEquals(array(0 => new SingleValue('4'), 4 => new SingleValue('4')), $struct->getSingleValues());
        $this->assertEquals(array(1 => new SingleValue('10'), 5 => new SingleValue('20')), $struct->getExcludes());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());
        $this->assertEquals(array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), $struct->getCompares());

        $struct->removeSingleValue(4);

        $this->assertTrue($struct->hasSingleValues());
        $this->assertEquals(array(0 => new SingleValue('4')), $struct->getSingleValues());
    }

    public function testUnsetExclude()
    {
        $struct = new FilterValuesBag('test', 'none', array(0 => new SingleValue('4'), 4 => new SingleValue('4')), array(1 => new SingleValue('10'), 5 => new SingleValue('20')), array(2 => new Range(10, 100), 6 => new Range(110, 200)), array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')));

        $this->assertEquals(array(0 => new SingleValue('4'), 4 => new SingleValue('4')), $struct->getSingleValues());
        $this->assertEquals(array(1 => new SingleValue('10'), 5 => new SingleValue('20')), $struct->getExcludes());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());
        $this->assertEquals(array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), $struct->getCompares());

        $struct->removeExclude(5);

        $this->assertTrue($struct->hasExcludes());
        $this->assertEquals(array(1 => new SingleValue('10')), $struct->getExcludes());
    }

    public function testUnsetRange()
    {
        $struct = new FilterValuesBag('test', 'none', array(0 => new SingleValue('4'), 4 => new SingleValue('4')), array(1 => new SingleValue('10'), 5 => new SingleValue('20')), array(2 => new Range(10, 100), 6 => new Range(110, 200)), array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')));

        $this->assertEquals(array(0 => new SingleValue('4'), 4 => new SingleValue('4')), $struct->getSingleValues());
        $this->assertEquals(array(1 => new SingleValue('10'), 5 => new SingleValue('20')), $struct->getExcludes());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());
        $this->assertEquals(array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), $struct->getCompares());
        $struct->removeRange(6);

        $this->assertTrue($struct->hasRanges());
        $this->assertEquals(array(2 => new Range(10, 100)), $struct->getRanges());
    }

    public function testUnsetExcludedRange()
    {
        $struct = new FilterValuesBag('test', 'none', array(0 => new SingleValue('4'), 4 => new SingleValue('4')), array(1 => new SingleValue('10'), 5 => new SingleValue('20')), array(2 => new Range(10, 100), 6 => new Range(110, 200)), array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), array(8 => new Range(12, 15)));

        $this->assertEquals(array(0 => new SingleValue('4'), 4 => new SingleValue('4')), $struct->getSingleValues());
        $this->assertEquals(array(1 => new SingleValue('10'), 5 => new SingleValue('20')), $struct->getExcludes());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());
        $this->assertEquals(array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), $struct->getCompares());
        $this->assertEquals(array(8 => new Range(12, 15)), $struct->getExcludedRanges());

        $struct->removeExcludedRange(8);

        $this->assertTrue($struct->hasRanges());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());

        $this->assertFalse($struct->hasExcludedRanges());
        $this->assertEquals(array(), $struct->getExcludedRanges());
    }

    public function testUnsetCompare()
    {
        $struct = new FilterValuesBag('test', 'none', array(0 => new SingleValue('4'), 4 => new SingleValue('4')), array(1 => new SingleValue('10'), 5 => new SingleValue('20')), array(2 => new Range(10, 100), 6 => new Range(110, 200)), array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')));

        $this->assertEquals(array(0 => new SingleValue('4'), 4 => new SingleValue('4')), $struct->getSingleValues());
        $this->assertEquals(array(1 => new SingleValue('10'), 5 => new SingleValue('20')), $struct->getExcludes());
        $this->assertEquals(array(2 => new Range(10, 100), 6 => new Range(110, 200)), $struct->getRanges());
        $this->assertEquals(array(3 => new Compare(10, '>'), 7 => new Compare(20, '<')), $struct->getCompares());

        $struct->removeCompare(7);

        $this->assertTrue($struct->hasCompares());
        $this->assertEquals(array(3 => new Compare(10, '>')), $struct->getCompares());
    }
}
