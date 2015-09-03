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
		"zip",
	],
	"5.5" => ["opcache"],
];

$versions = '';
foreach ($tags as $tag) {
	writeFile($tag);
	$versions .= "* {$tag} [(Dockerfile)]({$tag}/cli/Dockerfile)\n";

	foreach ($sapi as $sap) {
		$versions .= "  * {$tag}-{$sap} [(Dockerfile)]({$tag}/{$sap}/Dockerfile)\n";

		writeFile($tag, $sap);
	}
}


foreach ($sapi as $sap) {
	writeFile($sap);
}

writeFile();

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
	$install = "    && docker-php-ext-install ";

	$tag = '';
	if (! is_null($version) or ! is_null($type)) {
		$tag .= ":".$version;
		if (! is_null($version) and ! is_null($type)) $tag .= "-";
		$tag .= $type;
	}
	$from = "FROM php".$tag."\n";

	return $from."\n".
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
