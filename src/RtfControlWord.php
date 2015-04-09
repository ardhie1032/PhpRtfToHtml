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
class RtfControlWord extends RtfElement
{
	public $word;
	public $parameter;
	
	public function dump($level)
	{
		echo "<div style='color:green'>";
		$this->Indent($level);
		echo "WORD {$this->word} ({$this->parameter})";
		echo "</div>";
	}
}
