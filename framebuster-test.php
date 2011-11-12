<html>
	<head>
		<title>Frame Buster Test Script</title>
	</head>
	<body>
		<h1>Frame Buster Test Script</h1>
<?php

	function test_assert($condition, $message) {
		if ($condition) {
			echo 'Passed: ' . $message;
		}
		else {
			echo '<strong>FAILED:</strong> ' . $message;
		}
		echo '<br />';
	}

	require(dirname(__FILE__) . '/framebuster.php');
	/*
	 * Test for straight matches between pattern and hostname
	 */
	test_assert($myframebuster->does_host_match('jamesmckay.net', 'jamesmckay.net'), 'Straight match');
	test_assert(!$myframebuster->does_host_match('jamesmckay.net', 'test.jamesmckay.net'), 'Straight match - subdomain');
	test_assert(!$myframebuster->does_host_match('jamesmckay.net', 'testjamesmckay.net'), 'Straight match - contains domain');
	/*
	 * Test for matches between pattern and hostname allowing for subdomains
	 */
	test_assert($myframebuster->does_host_match('*.jamesmckay.net', 'jamesmckay.net'), 'Subdomains allowed - primary domain');
	test_assert($myframebuster->does_host_match('*.jamesmckay.net', 'test.jamesmckay.net'), 'Subdomains allowed - subdomain');
	test_assert(!$myframebuster->does_host_match('*.jamesmckay.net', 'testjamesmckay.net'), 'Subdomains allowed - contains domain');
	/*
	 * Test for straight matches between pattern and hostname/port
	 */
	test_assert($myframebuster->does_host_match('*.jamesmckay.net:8080', 'jamesmckay.net:8080'), 'Subdomains with port - primary domain');
	test_assert($myframebuster->does_host_match('*.jamesmckay.net:8080', 'test.jamesmckay.net:8080'), 'Subdomains with port - subdomain');
	test_assert(!$myframebuster->does_host_match('*.jamesmckay.net:8080', 'testjamesmckay.net:8080'), 'Subdomains with port - contains domain');
	test_assert(!$myframebuster->does_host_match('*.jamesmckay.net:8080', 'jamesmckay.net'), 'Subdomains with port - referrer without port');
?>
	</body>
</html>