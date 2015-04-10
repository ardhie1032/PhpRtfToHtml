<?php

/**
 * RTF Hexadecimal Control Symbol
 *
 * This class represents a hexadecimal control symbol for the RTF syntax. 
 * A hexadecimal control symbol is a set of characters that begins with an "\",
 * then is followed by a simple quote, then is followed by two hexadecimal
 * numbers.
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2015 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfHexaControlSymbol extends RtfControlSymbol
{
	
	/**
	 * The decimal representation of the hexadecimal digit.
	 * @var int
	 */
	private $_value = 0;
	/**
	 * Gets the decimal value of this object.
	 * @return int
	 */
	public function getValue()
	{
		return $this->_value;
	}
	/**
	 * Sets the value of this object from an hexadecimal value.
	 * @param string $value
	 */
	public function setValueFromHex($value)
	{
		$this->_value = hexdec($value);
	}
	/**
	 * Sets the value of this object from a decimal value.
	 * @param string $value
	 */
	public function setValueFromDec($value)
	{
		$this->_value = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfControlSymbol::equals()
	 */
	public function equals($object)
	{
		return parent::equals($object) && $this->_value === $object->_value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfControlSymbol::__toString()
	 */
	public function __toString()
	{
		return html_entity_decode($this->__toHtml());
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfControlSymbol::__toRtf()
	 */
	public function __toRtf()
	{
		return '\\'."'".dechex($this->_value);
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfControlSymbol::__toHtml()
	 */
	public function __toHtml()
	{
		return '&#'.$this->_value.';';
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfControlSymbol::__destruct()
	 */
	public function __destruct()
	{
		parent::__destruct();
		$this->_value = null;
	}
	
}
