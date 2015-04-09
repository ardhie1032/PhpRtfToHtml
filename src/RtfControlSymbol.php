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
class RtfControlSymbol extends RtfElement
{
	public $symbol;
	public $parameter = 0;

	public function dump($level)
	{
		echo "<div style='color:blue'>";
		$this->Indent($level);
		echo "SYMBOL {$this->symbol} ({$this->parameter})";
		echo "</div>";
	}
}
