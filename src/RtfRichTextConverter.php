<?php

/**
 * RtfRichTextConverter class file.
 *
 * This class represents a walker. This class takes into entry a group node,
 *
 * PHP version 5
 *
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfRichTextConverter
{
	
	/**
	 * Converts the given group with RtfText's and RtfControlSymbol's elements
	 * to another group with a RtfRichText's elements.
	 * @param RtfGroup $group
	 * @return RtfGroup $group
	 */
	public function convert(RtfGroup $group)
	{
		$newgroup = clone $group;
		$newgroup->children = $this->collect($group);
		return $newgroup;
	}
	
	/**
	 * Collects all children of given group. Converts all adjacent RtfText,
	 * RtfControlSymbol and/or RtfRichText to RtfRichText objects.
	 * @param RtfGroup $group
	 * @return RtfElement[]
	 */
	protected function collect(RtfGroup $group)
	{
		$children = array();
		$current = null;
		foreach($group->children as $child)
		{
			if($child instanceof RtfText
				|| $child instanceof RtfControlSymbol
				|| $child instanceof RtfRichText
			) {
				if($current===null)
				{
					$current = new RtfRichText();
					$children[] = $current;
				}
				$current->addText($child->__toString());
			}
			else
			{
				$current = null;
				if($child instanceof RtfGroup)
				{
					$newgroup = clone $child;
					$newgroup->children = $this->collect($child);
					$children[] = $newgroup;
				}
				else
				{
					$children[] = $child;
				}
			}
		}
		return $children;
	}
	
}
