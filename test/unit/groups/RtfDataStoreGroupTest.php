<?php

/**
 * RTF Data Store Group Test Case
 *
 * This class is made to test the RtfDataStoreGroup class.
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfDataStoreGroupTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * 
	 * @var RtfDataStoreGroup
	 */
	private $_object = null;
	
	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$this->_object = new RtfDataStoreGroup();
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
		$this->assertSame('\\datastore', $this->_object->__toString());
	}
	
	public function test__toRtf()
	{
		$this->assertSame('{\\*\\datastore}', $this->_object->__toRtf());
	}
	
	public function test__toHtml()
	{
		$this->assertSame('\\datastore', $this->_object->__toHtml());
	}
	
}
