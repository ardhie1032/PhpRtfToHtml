<?php

/**
 * RtfGroup class file.
 *
 * This class represents a group, in rtf syntax.
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
abstract class RtfGroup extends RtfElement
{
	/**
	 * The parent of this group.
	 * @var RtfGroup
	 */
	public $parent = null;
	/**
	 * All the children of this group, in order.
	 * @var RtfElement[]
	 */
	public $children = array();
	
	public abstract function getWord();
	
	public function GetType()
	{
		// No children?
		if(sizeof($this->children) == 0) return null;
		// First child not a control word?
		$child = $this->children[0];
		if(!($child instanceof RtfControlWord)) return null;
		return $child->word;
	}
	
	public function IsDestination()
	{
		// No children?
		if(sizeof($this->children) == 0) return null;
		// First child not a control symbol?
		$child = $this->children[0];
		if(!($child instanceof RtfControlSymbol)) return null;
		return $child->getSymbol() == '*';
	}

	public function getProperty($property)
	{
		foreach ($this->children as $child) {
			if ($child instanceof RtfControlWord && $child->word === $property) {
				return $child->parameter;
			}
		}

		return null;
	}

	public function getText()
	{
		foreach ($this->children as $child) {
			if ($child instanceof RtfText) {
				return $child->text;
			}
		}

		return null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::equals()
	 */
	public function equals($object)
	{
		if(!parent::equals($object))
			return false;
		
		if(count($this->children) !== count($object->children))
			return false;
		
		foreach($this->children as $i => $child)
		{
			if(!$child->equals($object->children[$i]))
				return false;
		}
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toString()
	 */
	public function dump($level = 0)
	{
		$str = '\\'.$this->getWord();
		foreach($this->children as $child)
		{
			$str .= $child->dump($level + 1);
		}
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toRtf()
	 */
	public function dumpRtf($level = 0)
	{
		$str = '{\\*\\'.$this->getWord();
		foreach($this->children as $child)
		{
			$str .= $child->dumpRtf($level + 1);
		}
		return $str.'}';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toHtml()
	 */
	public function dumpHtml($level = 0)
	{
		$str = "<div>";
		$str .= $this->indentHtml($level);
		$str .= "{ ".get_class($this).' : ';
		$str .= $this->getWord();
		$str .= "</div>\n";
		
		foreach($this->children as $child)
		{
// 			if($child instanceof RtfGenericGroup)
// 			{
// 				if ($child->IsDestination()) continue;
// 			}
			$str .= $child->dumpHtml($level + 2);
		}
		
		$str .= "<div>";
		$str .= $this->indentHtml($level);
		$str .= "}";
		$str .= "</div>\n";
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toString()
	 */
	public function __toString()
	{
		$str = '\\'.$this->getWord();
		foreach($this->children as $child)
		{
			$str .= $child->__toString();
		}
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toRtf()
	 */
	public function __toRtf()
	{
		$str = '{\\*\\'.$this->getWord();
		if($this->getParameter() != '' && $this->getParameter() != 0)
		{
			$str .= $this->getParameter();
		}
		foreach($this->children as $child)
		{
			$str .= $child->__toRtf();
		}
		return $str.'}';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__toHtml()
	 */
	public function __toHtml()
	{
		return $this->dumpHtml(0);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::free()
	 */
	public function free()
	{
		foreach($this->children as $child)
		{
			$child->free();
		}
		parent::free();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::__destruct()
	 */
	public function __destruct()
	{
		$this->parent = null;
		$this->children = array();
		parent::__destruct();
	}
	
}
