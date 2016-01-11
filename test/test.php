<?php

require "vendor/autoload.php";

$document = new Rtfparser();
$document->parse(file_get_contents("sample.rtf"));

file_put_contents('sample_dump.html', $document->root->dumpHtml());

$writer = new RtfHtml();
file_put_contents('sample.html', $writer->Format($document->root));
