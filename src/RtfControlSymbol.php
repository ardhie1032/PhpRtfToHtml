<?php

/**
 * RTF Control Symbol
 *
 * This class represents a control symbol for the RTF syntax. A control
 * symbol is a set of characters that begins with an "\", and then is 
 * followed by non word character. If follower character is a simple quote, 
 * then this class does not describe it, @see RtfHexaControlSymbol.
 *
 * PHP version 5
 *
 * @author     Alexander van Oostenrijk
 * @author     Arnaud PETIT
 * @copyright  2014 Alexander van Oostenrijk
 * @license    GNU GPLv2
 * @version    1
 * @link       http://www.websofia.com/2014/05/a-working-rtf-to-html-converter-in-php/
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfControlSymbol extends RtfElement
{
	
	/**
	 * The representation under default utf8 string of rtf symbols.
	 * @var string[]
	 */
	private static $_rtf_to_string = array(
		'~' => ' ',		// non breaking space.
		'|' => '',		// formula character in mac
		'-' => '-',		// optional hyphen
		'_' => '-',		// nonbreaking hyphen
		':' => '',		// subentry of index entry
		'*' => '',		// ignore if not known
	);
	
	/**
	 * The representation under html string of rtf symbols.
	 * @var string[]
	 */
	private static $_rtf_to_html = array(
		'~' => '&nbsp;',// non breaking space.
		'|' => '|',		// formula character in mac
		'-' => '-',		// optional hyphen
		'_' => '-',		// nonbreaking hyphen
		':' => '',		// subentry of index entry
		'*' => '',		// ignore if not known
	);
	
	/**
	 * 
	 * @var string
	 */
	private $_symbol = "";
	/**
	 * Sets the symbol of this class
	 * @param string $symbol
	 */
	public function setSymbol($symbol)
	{
		$this->_symbol = $symbol;
	}
	/**
	 * Gets the symbol of this class
	 * @return string
	 */
	public function getSymbol()
	{
		return $this->_symbol;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level=0)
	{
		echo "<div style='color:blue'>";
		echo $this->indentHtml($level);
		echo "SYMBOL &quot;".$this->__toHtml().'&quot;';
		echo "</div>\n";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::extractTextTree()
	 */
	public function extractTextTree()
	{
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		return parent::equals($object) && $this->_symbol === $object->_symbol;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toString()
	 */
	public function __toString()
	{
		if(isset(self::$_rtf_to_string[$this->_symbol]))
			return self::$_rtf_to_string[$this->_symbol];
		return $this->_symbol;
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toRtf()
	 */
	public function __toRtf()
	{
		return '\\'.$this->_symbol;
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toHtml()
	 */
	public function __toHtml()
	{
		if(isset(self::$_rtf_to_html[$this->_symbol]))
			return self::$_rtf_to_html[$this->_symbol];
		return $this->_symbol;
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__destruct()
	 */
	public function __destruct()
	{
		parent::__destruct();
		$this->_symbol = null;
	}
	
}
