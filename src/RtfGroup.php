<?php

/**
 * RTF parser/formatter
 *
 * This code reads RTF files and formats the RTF data to HTML.
 *
 * PHP version 5
 *
 * @author     Alexander van Oostenrijk
 * @copyright  2014 Alexander van Oostenrijk
 * @license    GNU GPLv2
 * @version    1
 * @link       http://www.websofia.com
 */
class RtfGroup extends RtfElement
{
	public $parent;
	public $children;

	public function __construct()
	{
		$this->parent = null;
		$this->children = array();
	}

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

	public function dump($level = 0)
	{
		echo "<div>";
		$this->Indent($level);
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
			$child->dump($level + 2);
		}

		echo "<div>";
		$this->Indent($level);
		echo "}";
		echo "</div>";
	}
}
