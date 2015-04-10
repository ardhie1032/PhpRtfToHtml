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
class RtfGroup extends RtfElement
{
	/**
	 * 
	 * @var RtfGroup
	 */
	public $parent = null;
	/**
	 * 
	 * @var RtfElement[]
	 */
	public $children = array();
	
	public function GetType()
	{
		// No children?
		if(sizeof($this->children) == 0) return null;
		// First child not a control word?
		$child = $this->children[0];
		if(get_class($child) != "RtfControlWord") return null;
		return $child->word;
	}
	
	public function IsDestination()
	{
		// No children?
		if(sizeof($this->children) == 0) return null;
		// First child not a control symbol?
		$child = $this->children[0];
		if(get_class($child) != "RtfControlSymbol") return null;
		return $child->symbol == '*';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level = 0)
	{
		echo "<div>";
		echo $this->indentHtml($level);
		echo "{";
		echo "</div>";
		
		foreach($this->children as $child)
		{
			if(get_class($child) == "RtfGroup")
			{
				if ($child->GetType() == "fonttbl") continue;
				if ($child->GetType() == "colortbl") continue;
				if ($child->GetType() == "stylesheet") continue;
				if ($child->GetType() == "info") continue;
				// Skip any pictures:
				if (substr($child->GetType(), 0, 4) == "pict") continue;
				if ($child->IsDestination()) continue;
			}
			$child->dumpHtml($level + 2);
		}
		
		echo "<div>";
		echo $this->indentHtml($level);
		echo "}";
		echo "</div>";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		if(!parent::equals($object))
			return false;
		
		if(count($this->children) !== count($object->children))
			return false;
		
		foreach($this->children as $i => $child)
		{
			if(!$child->equals($object->children[$i]))
				return false;
		}
		return true;
	}
	
}
