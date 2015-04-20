<?php

/**
 * RtfGenericGroup class file.
 * 
 * This class represents every group that were not resolved to a specific
 * group where parsing happened. Such a group is supposed to have a word
 * (which is the first control word of the group) that represents its 
 * behavior, then a parameter (the parameter of its word).
 * 
 * PHP version 5
 * 
 * @author     Arnaud PETIT
 * @copyright  2014 Arnaud PETIT
 * @license    GNU GPLv2
 * @version    1
 * @link       https://github.com/Anastaszor/PhpRtfToHtml
 */
class RtfGenericGroup extends RtfGroup
{
	
	/**
	 * The word relative to the group
	 * @var string
	 */
	private $_word = null;
	/**
	 * The parameter of the group.
	 * @var int
	 */
	private $_parameter = null;
	/**
	 * True if the group is special, i.e. if its word beginned with \*
	 * @var boolean
	 */
	private $_special = false;
	
	/**
	 * Gets the word of the generic group.
	 * @return string
	 */
	public function getWord()
	{
		return $this->_word;
	}
	/**
	 * Sets the word of the generic group
	 * @param string $word (alpha)
	 */
	public function setWord($word)
	{
		$this->_word = $word;
	}
	
	/**
	 * Gets the parameter of this group
	 * @return int
	 */
	public function getParameter()
	{
		return $this->_parameter;
	}
	
	/**
	 * Sets the parameter of this group
	 * @param int $parameter
	 */
	public function setParameter($parameter)
	{
		$this->_parameter = $parameter;
	}
	
	/**
	 * Gets if this group is special.
	 * @return boolean
	 */
	public function getSpecial()
	{
		return $this->_special;
	}
	
	/**
	 * Sets if this group is special.
	 * @param unknown $special
	 */
	public function setSpecial($special)
	{
		$this->_special = $special===true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::dumpHtml()
	 */
	public function dumpHtml($level = 0)
	{
		echo "<div>";
		echo $this->indentHtml($level);
		echo "{ ".get_class($this).' : ';
		if($this->_special) echo '\\*';
		echo $this->_word;
		if($this->_parameter!==null) echo ' ('.$this->_parameter.')';
		echo "</div>\n";
	
		foreach($this->children as $child)
		{
			if($child instanceof RtfGenericGroup)
			{
				if ($child->IsDestination()) continue;
			}
			$child->dumpHtml($level + 2);
		}
		
		echo "<div>";
		echo $this->indentHtml($level);
		echo "}";
		echo "</div>\n";
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfElement::extractTextTree()
	 */
	public function extractTextTree()
	{
		$root = new static();
		$root->parent = $this->parent;
		$root->setWord($this->getWord());
		$root->setParameter($this->getParameter());
		$root->setSpecial($this->getSpecial());
		foreach($this->children as $child)
		{
			if($child instanceof RtfGenericGroup)
			{
				if ($child->IsDestination()) continue;
			}
			$subtree = $child->extractTextTree();
			if($subtree !== null)
			{
				$root->children[] = $subtree;
			}
		}
		return (count($root->children)===0) ? null : $root;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::__toString()
	 */
	public function __toString()
	{
		$str = "\\";
		if($this->getSpecial())
		{
			$str .= "*\\";
		}
		$str .= $this->getWord();
		if($this->getParameter() != '' && $this->getParameter() != 0)
		{
			$str .= $this->getParameter();
		}
		foreach($this->children as $child)
		{
			$str .= $child->__toString();
		}
		return $str;
	}
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::__toRtf()
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
	 * @see RtfGroup::__toHtml()
	 */
	public function __toHtml()
	{
		$str = '\\';
		if($this->getSpecial())
		{
			$str .= '*\\';
		}
		$str .= $this->getWord();
		if($this->getParameter() != '' && $this->getParameter() != 0)
		{
			$str .= $this->getParameter();
		}
		$str = htmlentities($str);
		foreach($this->children as $child)
		{
			$str .= $child->__toHtml();
		}
		return $str;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RtfGroup::__destruct()
	 */
	public function __destruct()
	{
		$this->_word = null;
		$this->_parameter = null;
		$this->_special = null;
		parent::__destruct();
	}
	
}
