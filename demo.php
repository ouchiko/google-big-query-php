<?php

	include_once "GoogleConnect.class.php";
	include_once "GoogleBigQuery.class.php";

	// generate a valid google client without all the bollocks
	$gc = new GoogleConnect(
		"someapp", 
		"API KEY", // [https://console.developers.google.com/apis/credentials?project=<XXX> API key]
		"JSON SERVER FILE", // [https://console.developers.google.com/apis/credentials?project=<XXX> Service Account keys]
		array('https://www.googleapis.com/auth/bigquery')
	);

	// generate a (bigquery)sql object and do a fucking query!
	$bsql = new GoogleBigQuery($gc->get());
	$bsql->setProject("some-poi");
	$row=$bsql->query("SELECT * FROM [poi_testdata.airports] LIMIT 1", true);

	print "<XMP>";
	print_r($row);