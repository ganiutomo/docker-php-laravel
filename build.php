<?php

echo "Build Dockerfile!\n";

$tags = ["5.4","5.5","5.6"];
$sapi = ["apache","cli","fpm"];
$mods = [
	"5.4" => [
		"mbstring",
		"mcrypt",
		"mssql",
		"pdo_dblib",
		"pdo_mysql",
		"pdo_pgsql",
		"pgsql",
	],
	"5.5" => ["opcache"],
];

$versions = '';
foreach ($tags as $tag) {
	$versions .= "* {$tag} [(Dockerfile)]({$tag}/cli/Dockerfile)\n";

	foreach ($sapi as $sap) {
		$versions .= "  * {$tag}-{$sap} [(Dockerfile)]({$tag}/{$sap}/Dockerfile)\n";

		writeFile($tag, $sap);
	}

}

$modules = '';
foreach ($mods as $keys => $vals) {
	if ($keys === '5.5') $modules .= "\nPHP 5.5 and 5.6 only:\n";

	foreach ($vals as $mod) {
		$modules .= "* {$mod}\n";
	}
}

$read = <<<READ
[![Build Status](https://travis-ci.org/ganiutomo/docker-php-laravel.svg?branch=develop)](https://travis-ci.org/ganiutomo/docker-php-laravel)

# PHP Environment for Laravel Framework

Supported tags:
{$versions}
Additional module list:
{$modules}
READ;

chdir($_SERVER["PWD"]);
file_put_contents('README.md', $read);

function writeFile($version, $type) {
	chdir($_SERVER["PWD"]);

	$dockerfile = dockerFile($version, $type);

	if (! is_dir($version)) mkdir($version);
	chdir($version);

	if (! is_dir($type)) mkdir($type);
	chdir($type);

	return file_put_contents('Dockerfile', $dockerfile);
}

function dockerFile($version, $type) {
	$require = file_get_contents("require");
	$configs = file_get_contents("configure");
	$install = "    && docker-php-ext-install ";

	return "FROM php:{$version}-{$type}\n\n".
		$require."\n".
		$configs."\n".
		$install.implode(mods($version), ' ')."\n";
}

function mods($version) {
	global $mods;

	if ($version !== "5.4") $mod = array_merge($mods["5.4"], $mods["5.5"]);
	else $mod = $mods["5.4"];

	return $mod;
}
