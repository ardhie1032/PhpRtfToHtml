<?php

$source = dirname(__DIR__).DIRECTORY_SEPARATOR.'src';
$destination = __DIR__.DIRECTORY_SEPARATOR.'phprtftohtmllite.php';

if(is_file($destination))
	unlink($destination);

class MyRecursiveFilterIterator extends RecursiveFilterIterator
{
	public static $FILTERS = array(
		'__MACOSX',
	);
	public function accept() {
		return !in_array($this->current()->getFilename(),self::$FILTERS,true);
	}
}



$files = array();

$iterator = new RecursiveDirectoryIterator($source);
$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
$filter = new MyRecursiveFilterIterator($iterator);
$all_files = new RecursiveIteratorIterator($filter,RecursiveIteratorIterator::SELF_FIRST);

foreach($all_files as $file)
{
	$last = strrpos($file, DIRECTORY_SEPARATOR);
	$classname = substr(str_replace('.php', '', $file), $last+1);
	// get rid of the first <?php
	$content = substr(file_get_contents($file), 5);
	// get rid of all one-liner comments
	$content = preg_replace('#//.*'.PHP_EOL.'#', '', $content);
	// get rid of all others line breaks, tabs, and so on
	$content = str_replace(array("\r", "\n", "\t"), '', $content);
	// get rid of all multiline comments (that are now one liner)
	$content = preg_replace('#/\*.*?\*/#', '', $content);
	// wrap spaces around operators
	$operators = array(
		// regex => replacement
		'=' => '=',		// equality, affectation, and so on
		'\)' => ')',	// close parentesis
		'\(' => '(',	// open parentesis
		'\]' => ']',	// close bracket
		'\[' => '[',	// open bracket
		'\{' => '{',	// close brace
		'\}' => '}',	// open brace
		'!=' => '!=',	// negation
		'>' => '>',		// array affectation, comparison, etc
		'&' => '&',		// and, boolean and bitwise
		'\|' => '|',	// or, boolean and bitwise
		'<' => '<',		// comparison, bitwiseop, etc.
		'\.' => '.',	// string concatenation
		',' => ',',		// next element
		';' => ';',		// next statement
	);
	foreach($operators as $regex => $replacement)
	{
		$content = preg_replace('#\s*'.$regex.'\s*#', $replacement, $content);
	}
	
	
	// stores the living content to current array
	$files[$classname] = $content;
}

$dependancies = array();
foreach($files as $classname => $file)
{
	$superclassregex = 'extends\s*([\w\d_]+)';
	$interfacesregex = 'implements\s*(([\w\d_]+)(\s*,\s*[\w\d_]+)*';
	$superclass = array();
	if(strpos($file, 'extends')!==false)
	{
		preg_match('#'.$superclassregex.'#', $file, $superclass);
		if(isset($superclass[1]))
			$superclass = array($superclass[1]);
	}
	$interfaces = array();
	if(strpos($file, 'implements')!==false)
	{
		preg_match('#'.$interfacesregex.'#', $file, $interfaces);
	}
	$dependancies[$classname] = $superclass + $interfaces;
	
}
$dlist = array();
foreach($dependancies as $dpdname => $dpdval)
{
	if($dpdval===array())
	{
		$dlist[] = $dpdname;
		unset($dependancies[$dpdname]);
	}
}
while($dependancies!==array())
{
	foreach($dependancies as $dpdname => $dpdvalues)
	{
		$foundall = true;
		foreach($dpdvalues as $dpdsubname)
		{
			$foundall &= in_array($dpdsubname, $dlist);
		}
		if($foundall)
		{
			$dlist[] = $dpdname;
			unset($dependancies[$dpdname]);
		}
	}
}

$full_content = "<?php ";
foreach($dlist as $classname)
{
	$full_content .= $files[$classname];
}

file_put_contents($destination, $full_content);
