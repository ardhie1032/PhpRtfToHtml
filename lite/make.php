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
		return !in_array($this->current()->getFilename(),self::$FILTERS,true)
			&& preg_match('#\.php$#', $this->current()->getFilename());
	}
}

$files = array();
$iterator = new RecursiveDirectoryIterator($source);
$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
$f = new MyRecursiveFilterIterator($iterator);
$all = new RecursiveIteratorIterator($f,RecursiveIteratorIterator::SELF_FIRST);

// get all php files from src folder
foreach($all as $file)
{
	$last = strrpos($file, DIRECTORY_SEPARATOR);
	$classname = substr(str_replace('.php', '', $file), $last+1);
	// get rid of the first <?php
	$content = substr(file_get_contents($file), 5);
	// get rid of all one-liner comments
	$content = preg_replace('#//.*?'.PHP_EOL.'#', '', $content);
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

// build an array where all classes have an array of needed classes
$dependancies = array();
foreach($files as $classname => $file)
{
	$superclassregex = 'extends\s*([\w\d_]+)';
	$interfacesregex = 'implements\s*((([\w\d_]+)(\s*,\s*[\w\d_]+)*)';
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
		if(isset($interfaces[1]))
			$interfaces = array_map('trim', explode(',', $interfaces[1]));
	}
	$dependancies[$classname] = $superclass + $interfaces;
}

// builds a list where classes are appened in it where all their needed
// classes are already into the list
$dlist = array();
foreach($dependancies as $dpdname => $dpdval)
{
	if($dpdval===array())
	{
		$dlist[] = $dpdname;
		unset($dependancies[$dpdname]);
	}
}
$loops=0;
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
	// eternal recursion flashlight
	$loops++;
	// some class couldnt fulfill all of its requirements, some directory
	// that should have been included was not.
	if($loops>100)
		throw new Exception(
			'Impossible to set up hierarchy among classes, 100+ loops needed.'
		);
}

// builds the bigfile by including all the reduced files one by one in order
$full_content = "<?php ";
foreach($dlist as $classname)
{
	$full_content .= $files[$classname];
}

// finally, write the resulting file to destination.
file_put_contents($destination, $full_content);
