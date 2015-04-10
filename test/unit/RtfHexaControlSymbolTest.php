<?php

/**
 * RTF Hexa Control Symbol Test Case
 *
 * This class is made to test the RtfHexaControlSymbol class.
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfHexaControlSymbolTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * The object to test.
	 * @var RtfHexaControlSymbol
	 */
	private $_object = null;
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$this->_object = new RtfHexaControlSymbol();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		$this->_object->__destruct();
		$this->_object = null;
	}
	
	public function test_setDecValue()
	{
		$this->_object->setValueFromDec('233');
		$this->assertEquals('233', $this->_object->getValue());
	}
	
	public function test_setHexValue()
	{
		$this->_object->setValueFromHex('e9');
		$this->assertEquals('233', $this->_object->getValue());
	}
	
	public function test_refexion()
	{
		$this->_object->setValueFromDec('233');
		$this->assertTrue($this->_object->equals($this->_object));
	}
	
	public function test_equals()
	{
		$this->_object->setValueFromDec('233');
		$newobj = new RtfHexaControlSymbol();
		$newobj->setValueFromDec('233');
		$this->assertTrue($this->_object->equals($newobj));
		$this->assertTrue($newobj->equals($this->_object));
	}
	
	public function test_nequals()
	{
		$this->_object->setValueFromDec('233');
		$newobj = new RtfHexaControlSymbol();
		$newobj->setValueFromDec('234');
		$this->assertFalse($this->_object->equals($newobj));
		$this->assertFalse($newobj->equals($this->_object));
	}
	
	public function test_eacute__toString()
	{
		$this->_object->setValueFromDec('233');
		$this->assertSame('é', $this->_object->__toString());
	}
	
	public function test_eacute__toHtml()
	{
		$this->_object->setValueFromDec('233');
		$this->assertSame('&#233;', $this->_object->__toHtml());
	}
	
	public function test_eacute__toRtf()
	{
		$this->_object->setValueFromDec('233');
		$this->assertSame('\\\'e9', $this->_object->__toRtf());
	}
	
}
