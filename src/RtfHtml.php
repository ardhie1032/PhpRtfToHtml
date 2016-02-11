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
class RtfHtml
{
	
	private $output = "";

	/** @var RtfRtfGroup */
	private $root;

	/** @var RtfState */
	private $state = null;

	/** @var RtfState[] */
	private $states = array();
	
	public function Format($root)
	{
		$this->output = "";
		// Create a stack of states:
		$this->states = array();
		// Put an initial standard state onto the stack:
		$this->state = new RtfState();
		$this->states[] = $this->state;

		$this->root = $root;
		
		$this->FormatGroup($root);
		return $this->output;
	}

	/**
	 * @param RtfGroup $group
	 *
	 * @return string|void
	 */
	protected function FormatGroup($group)
	{
		if($group instanceof RtfControlWord)
			return $this->FormatControlWord($group);

		if($group instanceof RtfControlSymbol)
			return $this->FormatControlSymbol($group);

		if($group instanceof RtfText)
			return $this->FormatText($group);

		if($group instanceof RtfFontTableGroup)
			return $this->formatFontTable($group);

		if($group instanceof RtfColorTableGroup)
			return $this->formatColorTable($group);

		if($group instanceof RtfStylesheetGroup)
			return $this->formatStylesheet($group);

		if($group instanceof RtfListTableGroup)
			return $this->formatListTable($group);

		if($group instanceof RtfListOverrideTableGroup)
			return $this->formatListOverrideTable($group);

		if($group instanceof RtfInfoGroup)
			return $this->formatInfo($group);

		if($group instanceof RtfPnSecLevelGroup)
			return $this->formatPnSecLevel($group);

		if ($group->IsDestination())
			return;

		if(  $group instanceof RtfPictureGroup
			|| $group instanceof RtfThemeDataGroup
			|| $group instanceof RtfColorSchemeMappingGroup
			|| $group instanceof RtfLatentStylesGroup
			|| $group instanceof RtfDataStoreGroup
		)
			return;

		// Can we ignore this group?
		if (strpos($group->GetType(), "shp")   === 0) return;
		if (strpos($group->GetType(), "xmlns") === 0) return;

		// Push a new state onto the stack:
		$this->state = clone $this->state;
		$this->states[] = $this->state;
		
		foreach($group->children as $child)
		{
			$this->FormatGroup($child);
		}
		
		// Pop state from stack.
		array_pop($this->states);
		$this->state = $this->states[count($this->states)-1];
	}
	
	protected function FormatControlWord($word)
	{
		if($word->word === "plain") 
			$this->state->Reset();
		if($word->word === "b") 
			$this->state->bold = $word->parameter;
		if($word->word === "i") 
			$this->state->italic = $word->parameter;
		if($word->word === "ul") 
			$this->state->underline = $word->parameter;
		if($word->word === "ulnone") 
			$this->state->end_underline = $word->parameter;
		if($word->word === "strike") 
			$this->state->strike = $word->parameter;
		if($word->word === "v") 
			$this->state->hidden = $word->parameter;
		if($word->word === "fs") 
			$this->state->fontsize = ceil(($word->parameter / 24) * 16);
		if($word->word === "f")
			$this->state->fontstyle = $word->parameter;
		if($word->word === "super")
			$this->state->super = $word->parameter;
		if($word->word === "sub")
			$this->state->sub = $word->parameter;
		if($word->word === "cb")
			$this->state->bgColor = $word->parameter;
		if($word->word === "cf")
			$this->state->color = $word->parameter;

		if($word->word === "ql")
			$this->state->align = "left";
		if($word->word === "qc")
			$this->state->align = "center";
		if($word->word === "qr")
			$this->state->align = "right";
		if($word->word === "qj")
			$this->state->align = "justify";
		
		if($word->word === "par") 
			$this->output .= "<p style='text-align: {$this->state->align};'>";

		// Characters:
		if($word->word === "lquote") 
			$this->output .= "&lsquo;";
		if($word->word === "rquote") 
			$this->output .= "&rsquo;";
		if($word->word === "ldblquote") 
			$this->output .= "&ldquo;";
		if($word->word === "rdblquote") 
			$this->output .= "&rdquo;";
		if($word->word === "emdash") 
			$this->output .= "&mdash;";
		if($word->word === "endash") 
			$this->output .= "&ndash;";
		if($word->word === "bullet") 
			$this->output .= "&bull;";
		if($word->word === "u")
			$this->output .= "&loz;";
		if($word->word === "tab")
			$this->output .= "&#9;";
	}
	
	protected function BeginState()
	{
		$style = "";
		$class = "";
		if($this->state->bold)
			$style .= "font-weight:bold;";
		if($this->state->italic) 
			$style .= "font-style:italic;";
		if($this->state->underline) 
			$style .= "text-decoration:underline;";
		if($this->state->end_underline) 
			$style .= "text-decoration:none;";
		if($this->state->strike) 
			$style .= "text-decoration:strikethrough;";
		if($this->state->hidden) 
			$style .= "display:none;";
		if($this->state->super)
			$style .= "vertical-align: super; font-size: 0.7em;";
		if($this->state->sub)
			$style .= "vertical-align: sub; font-size: 0.7em;";
		if($this->state->fontsize != 0) 
			$style .= "font-size: {$this->state->fontsize}px;";

		if($this->state->fontstyle)
			$class .= "font-{$this->state->fontstyle} ";
		if($this->state->color)
			$class .= "color-{$this->state->color} ";
		if($this->state->bgColor)
			$class .= "bg-color-{$this->state->color} ";

		$this->output .= "<span style='{$style}' class='{$class}'>";
	}
	
	protected function EndState()
	{
		$this->output .= "</span>";
	}
	
	protected function FormatControlSymbol(RtfControlSymbol $symbol)
	{
		if($symbol->getSymbol() === '\'')
		{
			$this->BeginState();

			switch ($this->root->getCharset()) {
				default:
				case "ansi":
				case "pc":
				case "pca":
					$encoding = 'windows-1252';
					break;

				case "mac":
					$encoding = 'macroman';
					break;

			}
			$this->output .= htmlentities(chr($symbol->getValue()), ENT_QUOTES, $encoding);
			$this->EndState();
		}
		else {
			$this->BeginState();
			$this->output .= $symbol->__toHtml();
			$this->EndState();
		}
	}
	
	protected function FormatText($text)
	{
		$this->BeginState();
		$this->output .= $text->text;
		$this->EndState();
	}
	
	/**
	 * 
	 * @param RtfFontTableGroup $fontTable
	 */
	protected function formatFontTable(RtfFontTableGroup $fontTable)
	{
		$str = '<style>';
		$fontId = null;

		// TODO add some css classes // TODO better parsing with more classes
		foreach ($fontTable->children as $font) {
			if ($font instanceof RtfGroup) {
				$style = null;
				$fontId = null;
				foreach ($font->children as $_child) {
					if ($_child instanceof RtfText) {
						$style = "font-family: $_child->text;";
					}

					if ($_child instanceof RtfControlWord && $_child->word === "f") {
						$fontId = $_child->parameter;
					}
				}

				if ($fontId) {
					$str .= ".font-$fontId{ $style }";
				}
				continue;
			}

			if ($font instanceof RtfControlWord && $font->word === "f") {
				$fontId = $font->parameter;
			}

			if ($font instanceof RtfText) {
				$str .= ".font-$fontId{ font-family: $font->text; }";
			}
		}
 		$str .= '</style>';
		$this->output .= $str;
	}
	
	/**
	 * 
	 * @param RtfColorTableGroup $colorTable
	 */
	protected function formatColorTable(RtfColorTableGroup $colorTable)
	{
		$str = '<style>';
		$i = 1;

		$rgb_empty = array(
			'red'   => 0,
			'green' => 0,
			'blue'  => 0,
		);

		$rgb = $rgb_empty;

		$atLeastOne = false;

		foreach ($colorTable->children as $child) {
			if ($child instanceof RtfControlWord ) {
				$rgb[$child->word] = $child->parameter;
				$atLeastOne = true;
			}

			if ($atLeastOne && $child instanceof RtfText) {
				$color = "rgb({$rgb['red']},{$rgb['green']},{$rgb['blue']});";
				$str .= ".color-$i{color:$color}";
				$str .= ".bg-color-$i{background-color:$color}";

				$rgb = $rgb_empty;

				$i++;
			}
		}
		$str .= '</style>';
		$this->output .= $str;
	}
	
	/**
	 * 
	 * @param RtfStylesheetGroup $stylesheet
	 */
	protected function formatStylesheet(RtfStylesheetGroup $stylesheet)
	{
		$str = '<style>';
		// TODO add some css classes // TODO better parsing with more classes
		$str .= '</style>';
		$this->output .= $str;
	}
	
	/**
	 * 
	 * @param RtfListTableGroup $listTable
	 */
	protected function formatListTable(RtfListTableGroup $listTable)
	{
		$str = '<style>';
		// TODO add some css classes // TODO better parsing with more classes
		$str .= '</style>';
		$this->output .= $str;
	}
	
	/**
	 * 
	 * @param RtfListOverrideTableGroup $listOverrideTable
	 */
	protected function formatListOverrideTable(RtfListOverrideTableGroup $listOverrideTable)
	{
		$str = '<style>';
		// TODO add some css classes // TODO better parsing with more classes
		$str .= '</style>';
		$this->output .= $str;
	}
	
	/**
	 * 
	 * @param RtfInfoGroup $info
	 */
	protected function formatInfo(RtfInfoGroup $info)
	{
		// TODO add meta tags // TODO better parsing with more classes
	}
	
	/**
	 * 
	 * @param RtfPnSecLevelGroup $pnSecLevel
	 */
	protected function formatPnSecLevel(RtfPnSecLevelGroup $pnSecLevel)
	{
		$str = '<style>';
		// TODO add some css classes // TODO better parsing with more classes
		$str .= '</style>';
		$this->output .= $str;
	}
	
}
