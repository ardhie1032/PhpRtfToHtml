<?php

/**
 * RTF parser
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
 *
 * Sample of use:
 *
 * $reader = new RtfReader();
 * $rtf = file_get_contents("itc.rtf"); // or use a string
 * $reader->parse($rtf);
 * //$reader->root->dumpHtml(); // to see what the reader read
 * $formatter = new RtfHtml();
 * echo $formatter->Format($reader->root);
 */
class Rtfparser
{
	/**
	 * 
	 * @var RtfGroup
	 */
	public $root = null;
	/**
	 *
	 * @var RtfGroup
	 */
	private $group = null;
	/**
	 * 
	 * @var string
	 */
	private $rtf = null;
	/**
	 * 
	 * @var int
	 */
	private $pos = 0;
	/**
	 * 
	 * @var int
	 */
	private $len = 0;
	/**
	 * 
	 * @var string
	 */
	private $char = null;
	
	/**
	 * Gets the instanciation stats about this rtf.
	 * @var string => int
	 */
	private $_stats = array();
	/**
	 * All the classes where to map parsing groups.
	 * @var string[]
	 */
	private $_routes = array(
		'bkmkend' => 'RtfBookmarkEndGroup',
		'bkmkstart' => 'RtfBookmarkStartGroup',
		'colorschememapping' => 'RtfColorSchemeMappingGroup',
		'colortbl' => 'RtfColorTableGroup',
		'datastore' => 'RtfDataStoreGroup',
		'fonttbl' => 'RtfFontTableGroup',
		'info' => 'RtfInfoGroup',
		'latentstyles' => 'RtfLatentStylesGroup',
		'listtable' => 'RtfListTableGroup',
		'pict' => 'RtfPictureGroup',
		'pnseclvl' => 'RtfPnSecLevelGroup',
		'stylesheet' => 'RtfStylesheetGroup',
		'themedata' => 'RtfThemeDataGroup',
		'xmlnstbl' => 'RtfXmlNsTableGroup',
		'xmlopen' => 'RtfXmlOpenGroup',
	);
	
	/**
	 * Gets the instanciation stats about this parser.
	 * @return int[]
	 */
	public function getStats()
	{
		return $this->_stats;
	}
	
	protected function lookupGroupClass($word)
	{
		if(array_key_exists($word, $this->_routes))
			return $this->_routes[$word];
		return 'RtfGenericGroup';
	}
	
	protected function incrementStats($classname)
	{
		if(isset($this->_stats[$classname]))
			$this->_stats[$classname] = $this->_stats[$classname] + 1;
		else
			$this->_stats[$classname] = 1;
	}
	
	protected function getChar()
	{
		$this->char = $this->rtf[$this->pos++];
	}
	
	protected function parseStartGroup()
	{
		$this->getChar();
		if($this->char=='\\')
		{
			$special = false;
			$this->getChar();
			if($this->char==='*')
			{
				// if this is a star, then the group should begin 2 characters
				// later. A star indicates a non-recognizable special group.
				$this->getChar();
				$this->getChar();
				$special = true;
			}
			$word = "";
			while($this->isLetter())
			{
				$word .= $this->char;
				$this->getChar();
			}
			
			// Read parameter (if any) consisting of digits.
			// Paramater may be negative.
			$parameter = null;
			$negative = false;
			if($this->char == '-')
			{
				$this->getChar();
				$negative = true;
			}
			while($this->isDigit())
			{
				if($parameter == null) $parameter = 0;
				$parameter = $parameter * 10 + $this->char;
				$this->getChar();
			}
			if($parameter === null) $parameter = 1;
			if($negative) $parameter = -$parameter;
			
			
			if($word == "u")
			{
				// If this is \u, then the parameter will be followed by
				// a character.
			}
			else
			{
				// If the current character is a space, then
				// it is a delimiter. It is consumed.
				// If it's not a space, then it's part of the next
				// item in the text, so put the character back.
				if($this->char != ' ') $this->pos--;
			}
		}
		else
		{
			$word = "unknown";
			$parameter=null;
			$special=false;
		}
		
		$class = $this->lookupGroupClass($word);
		
		// Store state of document on stack.
		/* @var $group RtfGroup */
		$group = new $class();
		$this->incrementStats($class);
		if($group instanceof RtfGenericGroup)
		{
			$group->setWord($word);
			$group->setParameter($parameter);
			$group->setSpecial($special);
		}
		if($this->group != null)
		{
			$group->parent = $this->group;
		}
		if($this->root == null)
		{
			$this->group = $group;
			$this->root = $group;
		}
		else
		{
			$this->group->children[] = $group;
			$this->group = $group;
		}
	}
	
	protected function isLetter()
	{
		$ord = ord($this->char);
		if($ord >= 65 && $ord <= 90) return true;
		if($ord >= 97 && $ord <= 122) return true;
		return false;
	}
	
	protected function isDigit()
	{
		$ord = ord($this->char);
		if($ord >= 48 && $ord <= 57) return true;
		return false;
	}
	
	protected function parseEndGroup()
	{
		// Retrieve state of document from stack.
		$this->group = $this->group->parent;
	}
	
	protected function parseControlWord()
	{
		$this->getChar();
		$word = "";
		while($this->isLetter())
		{
			$word .= $this->char;
			$this->getChar();
		}
		
		// Read parameter (if any) consisting of digits.
		// Paramater may be negative.
		$parameter = null;
		$negative = false;
		if($this->char == '-')
		{
			$this->getChar();
			$negative = true;
		}
		while($this->isDigit())
		{
			if($parameter == null) $parameter = 0;
			$parameter = $parameter * 10 + $this->char;
			$this->getChar();
		}
		if($parameter === null) $parameter = 1;
		if($negative) $parameter = -$parameter;
		
		// If this is \u, then the parameter will be followed by
		// a character.
		if($word == "u")
		{
		}
		// If the current character is a space, then
		// it is a delimiter. It is consumed.
		// If it's not a space, then it's part of the next
		// item in the text, so put the character back.
		else
		{
			if($this->char != ' ') $this->pos--;
		}
		
		$rtfword = new RtfControlWord();
		$rtfword->word = $word;
		$rtfword->parameter = $parameter;
		$this->group->children[] = $rtfword;
	}
	
	protected function parseControlSymbol()
	{
		// Read symbol (one character only).
		$this->getChar();
		$symbol = $this->char;
		
		if($symbol == '\'')
		{
			// Symbols ordinarily have no parameter. However,
			// if this is \', then it is followed by a 2-digit hex-code:
			$this->getChar();
			$parameter = $this->char;
			$this->getChar();
			$parameter = $parameter . $this->char;
			$rtfsymbol = new RtfHexaControlSymbol();
			$rtfsymbol->setValueFromHex($parameter);
			$this->group->children[] = $rtfsymbol;
		}
		else
		{
			$rtfsymbol = new RtfControlSymbol();
			$rtfsymbol->symbol = $symbol;
			$this->group->children[] = $rtfsymbol;
		}
	}
	
	protected function parseControl()
	{
		// Beginning of an RTF control word or control symbol.
		// Look ahead by one character to see if it starts with
		// a letter (control world) or another symbol (control symbol):
		$this->getChar();
		$this->pos--;
		if($this->isLetter())
			$this->parseControlWord();
		else
			$this->parseControlSymbol();
	}
	
	protected function parseText()
	{
		// parse plain text up to backslash or brace,
		// unless escaped.
		$text = "";
		
		do
		{
			$terminate = false;
			$escape = false;
			
			// Is this an escape?
			if($this->char == '\\')
			{
				// Perform lookahead to see if this
				// is really an escape sequence.
				$this->getChar();
				switch($this->char)
				{
					case '\\': $text .= '\\'; break;
					case '{': $text .= '{'; break;
					case '}': $text .= '}'; break;
					default:
						// Not an escape. Roll back.
						$this->pos = $this->pos - 2;
						$terminate = true;
						break;
				}
			}
			else if($this->char == '{' || $this->char == '}')
			{
				$this->pos--;
				$terminate = true;
			}
			
			if(!$terminate && !$escape)
			{
				$text .= $this->char;
				$this->getChar();
			}
		}
		while(!$terminate && $this->pos < $this->len);
		
		$rtftext = new RtfText();
		$rtftext->text = $text;
		$this->group->children[] = $rtftext;
	}
	
	public function parse($rtf)
	{
		// Ignore \r and \n
		$this->rtf = str_replace(array("\n", "\r"), '', $rtf);
		$this->pos = 0;
		$this->len = strlen($this->rtf);
		$this->group = null;
		$this->root = null;
		
		while($this->pos < $this->len)
		{
			// Read next character:
			$this->getChar();
			
			// What type of character is this?
			switch($this->char)
			{
				case '{':
					$this->parseStartGroup();
					break;
				case '}':
					$this->parseEndGroup();
					break;
				case '\\':
					$this->parseControl();
					break;
				default:
					$this->parseText();
					break;
			}
		}
	}
	
}
