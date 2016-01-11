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
class RtfControlWord extends RtfElement
{
	/**
	 * 
	 * @var string
	 */
	public $word = "";
	/**
	 * 
	 * @var int
	 */
	public $parameter = 0;
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level = 0)
	{
		$str = "<div style='color:green'>";
		$str .= $this->indentHtml($level);
		$str .= "WORD {$this->word} ({$this->parameter})";
		$str .= "</div>";
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		return parent::equals($object) 
				&& $this->word === $object->word
				&& $this->parameter === $object->parameter;
	}
	
}
