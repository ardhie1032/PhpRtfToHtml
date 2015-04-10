<?php

/**
 * RTF Control Symbol Test Case
 * 
 * This class is made to test the RtfControlSymbol class.
 * 
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfControlSymbolTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The object to test.
	 * @var RtfControlSymbol
	 */
	private $_object = null;
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$this->_object = new RtfControlSymbol();
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
	
	
	public function test_setSymbol()
	{
		$this->_object->setSymbol('~');
		$this->assertSame('~', $this->_object->getSymbol());
	}
	
	public function test_extractTree()
	{
		$tree = $this->_object->extractTextTree();
		$this->assertSame($this->_object, $tree);
	}
	
	public function test_refexion()
	{
		$this->_object->setSymbol('~');
		$this->assertTrue($this->_object->equals($this->_object));
	}
	
	public function test_equals()
	{
		$this->_object->setSymbol('~');
		$newobj = new RtfControlSymbol();
		$newobj->setSymbol('~');
		$this->assertTrue($this->_object->equals($newobj));
		$this->assertTrue($newobj->equals($this->_object));
	}
	
	public function test_nequals()
	{
		$this->_object->setSymbol('~');
		$newobj = new RtfControlSymbol();
		$newobj->setSymbol('#');
		$this->assertFalse($this->_object->equals($newobj));
		$this->assertFalse($newobj->equals($this->_object));
	}
	
	public function test_nbsp__toString()
	{
		$this->_object->setSymbol('~');
		$this->assertSame(' ', $this->_object->__toString());
	}
	
	public function test_nbsp__toHtml()
	{
		$this->_object->setSymbol('~');
		$this->assertSame('&nbsp;', $this->_object->__toHtml());
	}
	
	public function test_nbsp__toRtf()
	{
		$this->_object->setSymbol('~');
		$this->assertSame('\\~', $this->_object->__toRtf());
	}
	
	public function test_formula__toString()
	{
		$this->_object->setSymbol('|');
		$this->assertSame('', $this->_object->__toString());
	}
	
	public function test_formula__toHtml()
	{
		$this->_object->setSymbol('|');
		$this->assertSame('|', $this->_object->__toHtml());
	}
	
	public function test_formula__toRtf()
	{
		$this->_object->setSymbol('|');
		$this->assertSame('\\|', $this->_object->__toRtf());
	}
	
	public function test_ohyp__toString()
	{
		$this->_object->setSymbol('-');
		$this->assertSame('-', $this->_object->__toString());
	}
	
	public function test_ohyp__toHtml()
	{
		$this->_object->setSymbol('-');
		$this->assertSame('-', $this->_object->__toHtml());
	}
	
	public function test_ohyp__toRtf()
	{
		$this->_object->setSymbol('-');
		$this->assertSame('\\-', $this->_object->__toRtf());
	}
	
	public function test_nbhyp__toString()
	{
		$this->_object->setSymbol('_');
		$this->assertSame('-', $this->_object->__toString());
	}
	
	public function test_nbhyp__toHtml()
	{
		$this->_object->setSymbol('_');
		$this->assertSame('-', $this->_object->__toHtml());
	}
	
	public function test_nbhyp__toRtf()
	{
		$this->_object->setSymbol('_');
		$this->assertSame('\\_', $this->_object->__toRtf());
	}
	
	public function test_subnt__toString()
	{
		$this->_object->setSymbol(':');
		$this->assertSame('', $this->_object->__toString());
	}
	
	public function test_subnt__toHtml()
	{
		$this->_object->setSymbol(':');
		$this->assertSame('', $this->_object->__toHtml());
	}
	
	public function test_subnt__toRtf()
	{
		$this->_object->setSymbol(':');
		$this->assertSame('\\:', $this->_object->__toRtf());
	}
	
	public function test_ign__toString()
	{
		$this->_object->setSymbol('*');
		$this->assertSame('', $this->_object->__toString());
	}
	
	public function test_ign__toHtml()
	{
		$this->_object->setSymbol('*');
		$this->assertSame('', $this->_object->__toHtml());
	}
	
	public function test_ign__toRtf()
	{
		$this->_object->setSymbol('*');
		$this->assertSame('\\*', $this->_object->__toRtf());
	}
	
}
