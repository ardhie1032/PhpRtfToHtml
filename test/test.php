<?php

require __DIR__."/../vendor/autoload.php";

$document = new Rtfparser();
$document->parse(file_get_contents(__DIR__."/sample.rtf"));

file_put_contents(__DIR__."/sample_dump.html", $document->root->dumpHtml());

$writer = new RtfHtml();
file_put_contents(__DIR__."/sample.html", $writer->Format($document->root));
