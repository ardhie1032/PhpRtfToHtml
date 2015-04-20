<?php

/**
 * RTF Generic Group Test Case
 *
 * This class is made to test the RtfGenericGroup class.
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfGenericGroupTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var RtfGenericGroup
	 */
	private $_object = null;
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$this->_object = new RtfGenericGroup();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		$this->_object = null;
	}
	
	
	public function testWord()
	{
		$this->_object->setWord('word');
		$this->assertSame('word', $this->_object->getWord());
	}
	
	public function testParameter()
	{
		$this->_object->setParameter('42');
		$this->assertSame('42', $this->_object->getParameter());
	}
	
	public function testSpecialTrue()
	{
		$this->_object->setSpecial(true);
		$this->assertTrue($this->_object->getSpecial());
	}
	
	public function testSpecialFalse()
	{
		$this->_object->setSpecial(false);
		$this->assertFalse($this->_object->getSpecial());
	}
	
	public function test__toString()
	{
		$this->_object->setWord('word');
		$this->assertSame('\\word', $this->_object->__toString());
	}
	
	public function test__toStringWithParam()
	{
		$this->_object->setWord('word');
		$this->_object->setParameter('42');
		$this->assertSame('\\word42', $this->_object->__toString());
	}
	
	public function test__toStringWithSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setSpecial(true);
		$this->assertSame('\\*\\word', $this->_object->__toString());
	}
	
	public function test__toStringWithParamAndSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setParameter('42');
		$this->_object->setSpecial(true);
		$this->assertSame('\\*\\word42', $this->_object->__toString());
	}
	
	public function test__toRtf()
	{
		$this->_object->setWord('word');
		$this->assertSame('{\\*\\word}', $this->_object->__toRtf());
	}
	
	public function test__toRtfWithParam()
	{
		$this->_object->setWord('word');
		$this->_object->setParameter('42');
		$this->assertSame('{\\*\\word42}', $this->_object->__toRtf());
	}
	
	public function test__toRtfWithSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setSpecial(true);
		$this->assertSame('{\\*\\word}', $this->_object->__toRtf());
	}
	
	public function test__toRtfWithParamAndSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setSpecial(true);
		$this->_object->setParameter('42');
		$this->assertSame('{\\*\\word42}', $this->_object->__toRtf());
	}
	
	public function test__toHtml()
	{
		$this->_object->setWord('word');
		$this->assertSame('\\word', $this->_object->__toHtml());
	}
	
	public function test__toHtmlWithParam()
	{
		$this->_object->setWord('word');
		$this->_object->setParameter('42');
		$this->assertSame('\\word42', $this->_object->__toHtml());
	}
	
	public function test__toHtmlWithSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setSpecial(true);
		$this->assertSame('\\*\\word', $this->_object->__toHtml());
	}
	
	public function test__toHtmlWithParamAndSpecial()
	{
		$this->_object->setWord('word');
		$this->_object->setParameter('42');
		$this->_object->setSpecial(true);
		$this->assertSame('\\*\\word42', $this->_object->__toHtml());
	}
	
}
