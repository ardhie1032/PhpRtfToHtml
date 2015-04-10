<?php

/**
 * RTF Control Symbol
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
class RtfControlSymbol extends RtfElement
{
	/**
	 * 
	 * @var string
	 */
	public $symbol = "";
	/**
	 * 
	 * @var int
	 */
	public $parameter = 0;
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level=0)
	{
		echo "<div style='color:blue'>";
		echo $this->indentHtml($level);
		echo "SYMBOL {$this->symbol} (&#{$this->parameter};)";
		echo "</div>";
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
		return parent::equals($object) 
				&& $this->symbol === $object->symbol
				&& $this->parameter === $object->parameter;
	}
	
}
