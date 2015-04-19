<?php

/**
 * RTF XML Namespace Table Group Test Case
 *
 * This class is made to test the RtfXmlNsTableGroup class.
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfXmlNsTableGroupTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var RtfXmlNsTableGroup
	 */
	private $_object = null;
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$this->_object = new RtfXmlNsTableGroup();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		$this->_object = null;
	}
	
	
	public function test__toString()
	{
		$this->assertSame('\\xmlnstbl', $this->_object->__toString());
	}
	
	public function test__toRtf()
	{
		$this->assertSame('{\\*\\xmlnstbl}', $this->_object->__toRtf());
	}
	
	public function test__toHtml()
	{
		$this->assertSame('\\xmlnstbl', $this->_object->__toHtml());
	}
	
}
