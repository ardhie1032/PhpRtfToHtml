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
 * @link       http://www.websofia.com/2014/05/a-working-rtf-to-html-converter-in-php/
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfState
{
	
	public function __construct()
	{
		$this->Reset();
	}
	
	public function Reset()
	{
		$this->bold = false;
		$this->italic = false;
		$this->underline = false;
		$this->end_underline = false;
		$this->strike = false;
		$this->hidden = false;
		$this->fontsize = 0;
	}
	
}
