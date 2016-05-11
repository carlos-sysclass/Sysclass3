<?php
	$current_version = PHP_VERSION;
	//$current_version = "5.3.5";
	if (version_compare($current_version, '5.0.0') < 0) {
		throw new Exception("At least php 5.0 is required!");
		die(-1);
	}

	$versions_files = scandir(__DIR__ . "/php-versions-wrapper");

	foreach($versions_files as $file) {
		if ($file == '.' || $file == '..') {
			continue;
		}
		$version = basename($file, ".php");

		if ($version == "all") {
			require_once(__DIR__ . "/php-versions-wrapper/" . $file);
		} else {
			if (version_compare($current_version, $version) < 0) {
				require_once(__DIR__ . "/php-versions-wrapper/" . $file);
			}			
		}
	}
?>