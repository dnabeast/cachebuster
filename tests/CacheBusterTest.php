<?php

use Typesaucer\CacheBuster\CacheBuster;

class CacheBusterTest extends PHPUnit_Framework_TestCase
{

	function test_it_returns_itself_when_in_a_local_environment_with_css()
	{
		$cachebuster = new CacheBuster;
		$parsed = $cachebuster->fire('css/style.css', 'local');
		$expected = 'css/style.css';

		return $this->assertEquals(
			$parsed,
			$expected
		);
	}

	function test_it_returns_a_random_string_of_eight_characters()
	{
		$cachebuster = new CacheBuster;
		$parsed = $cachebuster->randomString();
		return $this->assertRegExp('/^[\w\d]{8}$/', $parsed);
	}

	function test_it_returns_the_current_build_name_if_it_exists()
	{
		$cachebuster = new CacheBuster;
		$arrayOfMockedFilenames = ['.','..','.DS_store','admin.dshfshcd.css','style.dshfshcd.css'];
		$filename = 'style.css';
		$parsed = $cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertRegExp('/^style\.[\w\d]{8}\.css$/', $parsed);
	}

	function test_it_returns_the_current_build_name_if_it_exists_with_admin()
	{
		$cachebuster = new CacheBuster;
		$arrayOfMockedFilenames = ['.','..','.DS_store','admin.dshfshcd.css','style.dshfshcd.css'];
		$filename = 'admin.css';
		$parsed = $cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);

		$parsed = $cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertRegExp('/^admin\.[\w\d]{8}\.css$/', $parsed);
	}
	function test_it_returns_false_if_no_build_name_exists()
	{
		$cachebuster = new CacheBuster;

		$arrayOfMockedFilenames = ['.','..','.DS_store'];
		$filename = 'style.css';
		$parsed = $cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertFalse($parsed);
	}
}
