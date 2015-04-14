<?php

/**
 * RtfFontTableGroup class file.
 * 
 * This class represents a font table group into the rtf syntax.
 * 
 * PHP version 5
 * 
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfFontTableGroup extends RtfGroup
{
	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::getWord()
	 */
	public function getWord()
	{
		return 'fonttbl';
	}
	
}
