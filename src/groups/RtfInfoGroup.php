<?php

/**
 * RtfInfoGroup class file.
 *
 * This class represents a info group into the rtf syntax.
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfInfoGroup extends RtfGroup
{
	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::getWord()
	 */
	public function getWord()
	{
		return 'info';
	}
	
}
