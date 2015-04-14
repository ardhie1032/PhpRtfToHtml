<?php

/**
 * RtfRichText class file.
 *
 * This class represents an utf-8 text. It is composed of basic 8-bits ascii
 * rtf-compatible text, and an utf-8 version of all RtfControlSymbols.
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfRichText extends RtfElement
{
	
	/**
	 *
	 * @var string
	 */
	public $text = "";
	
	/**
	 * Appends some text to the end of current text.
	 * @param string $text
	 */
	public function addText($text)
	{
		$this->text .= $text;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level=0)
	{
		echo "<div style='color:red'>";
		echo $this->indentHtml($level);
		echo 'RICHTEXT '.htmlentities($this->text);
		echo "</div>";
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
