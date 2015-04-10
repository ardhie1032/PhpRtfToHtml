<?php

/**
 * RTF Base Element
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
abstract class RtfElement
{
	
	/**
	 * Returns a non breakable space of size 4 for html indentation.
	 * @param int $level
	 * @return string
	 */
	protected function indentHtml($level)
	{
		return str_repeat("&nbsp;", 4*$level);
	}
	
	/**
	 * Returns a tabulation for non html indentation.
	 * @param unknown $level
	 * @return string
	 */
	protected function indent($level)
	{
		return str_repeat("\t", $level);
	}
	
	/**
	 * Dumps the content of this element into an html string.
	 * @param int $level
	 * @return string
	 */
	public function dumpHtml($level=0)
	{
		return '<div>'.$this->indentHtml($level).$this->dump($level).'</div>';
	}
	
	/**
	 * Returns a string representation of this element into a simple string
	 * @param int $level
	 * @return string
	 */
	public function dump($level=0)
	{
		return $this->indent($level).'ELEMENT UNKNOWN';
	}
	
	/**
	 * Compares two objects from this library. This function must be inherited.
	 * @param object $object
	 * @return boolean true if both objects are equal.
	 */
	public function equals($object)
	{
		return is_object($object) && ($object instanceof RtfElement 
					|| is_subclass_of($object, get_class($this)));
	}
	
}
