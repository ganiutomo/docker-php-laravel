<?php

echo "Build Dockerfile!\n";

$tags = ["5.4","5.5","5.6"];
$sapi = ["apache","cli","fpm"];
$mods = [
	"5.5" => ["opcache"],
	"5.6" => ["opcache"],
];

$versions = '';
foreach ($tags as $tag) {
	$versions .= "* {$tag} [(Dockerfile)]({$tag}/cli/Dockerfile)\n";

	foreach ($sapi as $sap) {
		$versions .= "  * {$tag}-{$sap} [(Dockerfile)]({$tag}/{$sap}/Dockerfile)\n";

		writeFile($tag, $sap);
	}
}


function writeFile($version = NULL, $type = NULL) {
	chdir($_SERVER["PWD"]);

	$dockerfile = dockerFile($version, $type);

	if (! is_null($version)) {
		if(! is_dir($version)) mkdir($version);
		chdir($version);
	}

	if (! is_null($type)) {
		if(! is_dir($type)) mkdir($type);
		chdir($type);
	}

	return file_put_contents('Dockerfile', $dockerfile);
}

function dockerFile($version = NULL, $type = NULL) {
	$require = file_get_contents("require");
	$configs = file_get_contents("configure");
	$install = file_get_contents("install");
	$cleanup = file_get_contents("cleanup");

	$tag = '';
	if (! is_null($version) or ! is_null($type)) {
		$tag .= ":".$version;
		if (! is_null($version) and ! is_null($type)) $tag .= "-";
		$tag .= $type;
	}
	$from = "FROM php".$tag."\n";

	return $from."\n".
		$require.
		$configs.
		rtrim($install).
		mods($version).
		$cleanup;
}

function mods($version) {
	global $mods;

	if (array_key_exists($version, $mods))
	$mod = " \\\n    ".implode($mods[$version], " \\\n    ");

	return $mod." \\\n";
}
