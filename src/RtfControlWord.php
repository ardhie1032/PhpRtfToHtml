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
 * @link       http://www.websofia.com
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
		echo "<div style='color:green'>";
		echo $this->indentHtml($level);
		echo "WORD {$this->word} ({$this->parameter})";
		echo "</div>";
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
