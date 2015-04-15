<?php

/**
 * RtfTextGatherer class file.
 *
 * This class represents a walker. This class takes into entry a group node,
 * and then gathers all texts into an array of brute text. An implode of the
 * array will suffice to produce the inner string of the full document tree
 * from given group.
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfTextGatherer
{
	
	/**
	 * Gather all texts from the tree into one one-dimensional array
	 * @param RtfGroup $group
	 * @return string[]
	 */
	public function gather(RtfGroup $group)
	{
		$texts = array();
		foreach($group->children as $child)
		{
			if($child instanceof RtfText
				|| $child instanceof RtfRichText
				|| $child instanceof RtfControlSymbol
			) {
				$texts[] = $child->__toString();
			}
			if($child instanceof RtfGroup)
			{
				$texts = array_merge($texts, $this->gather($child));
			}
		}
		return $texts;
	}
	
}
