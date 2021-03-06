<?php

/**
 * RTF parser/formatter
 *
 * This code reads RTF files and formats the RTF data to HTML.
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
class RtfText extends RtfElement
{
	/**
	 * 
	 * @var string
	 */
	public $text;
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level=0)
	{
		$str = "<div style='color:red'>";
		$str .= $this->indentHtml($level);
		$str .= "TEXT {$this->text}";
		$str .= "</div>";
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::extractTextTree()
	 */
	public function extractTextTree()
	{
		if(trim($this->text)==="")
			return null;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		return parent::equals($object) && $this->text === $object->text;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toString()
	 */
	public function __toString()
	{
		return $this->text;
	}
	
}
