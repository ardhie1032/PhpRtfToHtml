<?php

/**
 * RtfBookmarkStartGroup class file.
 *
 * This class represents a start of a bookmark group into the rtf syntax.
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfBookmarkStartGroup extends RtfGroup
{
	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::getWord()
	 */
	public function getWord()
	{
		return 'bkmkstart';
	}
	
}
