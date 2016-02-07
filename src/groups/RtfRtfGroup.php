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
class RtfRtfGroup extends RtfGroup
{
	private $charset;

	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::getWord()
	 */
	public function getWord()
	{
		return 'rtf';
	}

	function getCharset() {
		if ($this->charset !== null) {
			return $this->charset;
		}

		$charsets = array(
			"ansi",
			"mac",
			"pc",
			"pca",
		);

		foreach ($charsets as $charset) {
			if ($this->getProperty($charset)) {
				return $this->charset = $charset;
			}
		}

		return "ansi";
	}
}
