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
class RtfText extends RtfElement
{
	public $text;

	public function dump($level)
	{
		echo "<div style='color:red'>";
		$this->Indent($level);
		echo "TEXT {$this->text}";
		echo "</div>";
	}
}
