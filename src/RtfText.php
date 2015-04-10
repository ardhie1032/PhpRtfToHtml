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
	public function dumpHtml($level)
	{
		echo "<div style='color:red'>";
		echo $this->indentHtml($level);
		echo "TEXT {$this->text}";
		echo "</div>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		return parent::equals($object) && $this->text === $object->text;
	}
	
}
