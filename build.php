<?php

echo "Build Dockerfile!\n";

$tags = ["5.4","5.5","5.6"];
$sapi = ["apache","cli","fpm"];

foreach ($tags as $tag) {
	foreach ($sapi as $sap) {
		writeFile($tag, $sap);
	}
}

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
	$install = "RUN docker-php-ext-install ";

	return "FROM php:{$version}-{$type}\n\n".
	$require."\n".
	$configs."\n".
	$install.mods($version)."\n";
}

function mods($version) {
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

	if ($version !== "5.4") $mod = array_merge($mods["5.4"], $mods["5.5"]);
	else $mod = $mods["5.4"];
	
	return implode($mod, ' ');
}
