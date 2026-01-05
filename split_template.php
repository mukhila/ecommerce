<?php

$sourceFile = 'c:/xampp/htdocs/ecommerce/resources/views/temp_reference.blade.php';
$layoutFile = 'c:/xampp/htdocs/ecommerce/resources/views/layouts/master.blade.php';
$indexFile = 'c:/xampp/htdocs/ecommerce/resources/views/frontend/index.blade.php';

$content = file_get_contents($sourceFile);

// Replace asset paths
$content = str_replace('src="../assets/', 'src="{{ asset(\'frontassets/', $content);
$content = str_replace('href="../assets/', 'href="{{ asset(\'frontassets/', $content);
// Also replace closing parentheses for asset calls logic if needed, but simple replacement works for the prefix.
// The regex above closes 'frontassets/...' with ' and )} ? No, blade syntax is {{ asset('...') }}.
// So src="../assets/css/style.css" -> src="{{ asset('frontassets/css/style.css') }}"
// My previous simple replace only does the start. I need to close it.

// Better regex replacement
$content = preg_replace('/src="\.\.\/assets\/([^"]+)"/', 'src="{{ asset(\'frontassets/$1\') }}"', $content);
$content = preg_replace('/href="\.\.\/assets\/([^"]+)"/', 'href="{{ asset(\'frontassets/$1\') }}"', $content);

// Split content
// Header ends at line 1087 (inclusive of </header>)
// Content starts at line 1088
// Footer starts at line 2581 (<!-- Footer Section Start -->)

$lines = explode("\n", $content);
$headerLines = array_slice($lines, 0, 1088);
$contentLines = array_slice($lines, 1088, 2580 - 1088);
$footerLines = array_slice($lines, 2580);

$headerStr = implode("\n", $headerLines);
$contentStr = implode("\n", $contentLines);
$footerStr = implode("\n", $footerLines);

// Create Layout
$layoutContent = $headerStr . "\n" . 
    "    @yield('content')\n" . 
    $footerStr;

// Create Index
$indexContent = "@extends('layouts.master')\n\n" .
    "@section('title', 'Home')\n\n" .
    "@section('content')\n" . 
    $contentStr . "\n" . 
    "@endsection";

// Ensure directories exist
if (!is_dir(dirname($layoutFile))) mkdir(dirname($layoutFile), 0777, true);
if (!is_dir(dirname($indexFile))) mkdir(dirname($indexFile), 0777, true);

file_put_contents($layoutFile, $layoutContent);
file_put_contents($indexFile, $indexContent);

echo "Files created successfully.";
