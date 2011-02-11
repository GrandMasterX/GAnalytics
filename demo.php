<?php
	// Include the Google Analytics data import class
	include_once 'GAnalytics/GAnalytics.php';

	// Set up your Google Analytics credentials
	$gaEmail        = 'XXXXXX';
	$gaPassword     = 'XXXXXX';

	// Set up a period of time to get data for
	$statsStartDate = date('Y-m-d', time() - 8 * 24 * 60 *60); //one week ahead
	$statsEndDate   = date('Y-m-d', time() - 1 * 24 * 60 *60); //yesterday

	// Get and store the query data code from Google Analytics Data Feed Query Explorer
	// http://code.google.com/apis/analytics/docs/gdata/gdataExplorer.html
	//
	// You will set here your own query data url
	$gaUrl = "https://www.google.com/analytics/feeds/data?" .
			  "ids=ga%XXXXXX&" .
			  "dimensions=ga%3ApagePath" .
			  "&metrics=ga%3Avisits&" .
			  "filters=ga%3ApagePath%3D~anunt%5C%3Fid%3D*&" .
			  "sort=-ga%3Avisits&" .
			  "start-date={$statsStartDate}&" .
			  "end-date={$statsEndDate}&" .
		      "max-results=5";

	// Keep your connection data into a config array
	$config = array('email'      => $gaEmail,
					'password'   => $gaPassword,
					'requestUrl' => $gaUrl,
	);

	// Create a new GAnalytics object
	$ga = new GAnalytics($config);

	try {
		
		// Call the Google Analytics API request in here
		$gaResult = $ga->call();

		// If the call was successful - do your magic in here
		// You have to parse the Atom Feed XML response and gather you stats
		// This can be achieved with a SimpleXML tree traversing
		// or with a preg_match_call() to make your life easier
		preg_match_all("@<dxp:dimension name='ga:pagePath' value='/anunt\?id=([0-9]{1,})'/>@", $gaResult, $matches);

		// A dummy data rendering here...
		var_dump($matches, $matches[1], $gaResult);

	} catch (Exception $e) {

		// Log your error here
		echo "GAnalytics Connection error ({$e->getCode()}): {$e->getMessage()}";
	}
