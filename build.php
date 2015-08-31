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
	$mods = [
		"mbstring",
	    "mcrypt",
	    "mssql",
	    "pdo_dblib",
	    "pdo_mysql",
	    "pdo_pgsql",
	    "pgsql",
	];

	$mod5 = ["opcache"];

	$require = file_get_contents("require");
	$configs = file_get_contents("configure");
	$install = "RUN docker-php-ext-install ";

	if ($version !== "5.4") $mod = array_merge($mods, $mod5);
	else $mod = $mods;

	return "FROM php:{$version}-{$type}\n\n".
	$require."\n".
	$configs."\n".
	$install.implode($mod, ' ')."\n";
}
