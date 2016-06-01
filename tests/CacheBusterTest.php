<?php

use DNABeast\CacheBuster\CacheBuster;

class CacheBusterTest extends PHPUnit_Framework_TestCase
{

	public function setup()
	{
		$this->cachebuster = new CacheBuster('testing');
		$cssDirectory = $_SERVER['PWD'].'/tests/public/css/';
		fopen($cssDirectory.'style.css', 'w') or die('Cannot open file:  '.$my_file);
	}

	function test_it_returns_itself_when_in_a_local_environment_with_css()
	{
		$this->cachebuster = new CacheBuster('local');
		$parsed = $this->cachebuster->fire('css/style.css');
		$expected = 'css/style.css';

		return $this->assertEquals(
			$parsed,
			$expected
		);
	}

	function test_it_creates_a_build_css_file_with_a_stamp_if_none_exists()
	{
		$cssDirectory = $_SERVER['PWD'].'/tests/public/css/';

		$createfile = fopen($cssDirectory.'style.css', 'w') or die('Cannot open file:  '.$my_file);

		$parsed = $this->cachebuster->fire('css/style.css', '', 'production', 'testing');
		$expected = 'css/style.css';

		unlink($cssDirectory.'style.css');
		unlink($cssDirectory.'/../'.$parsed);

		return $this->assertRegExp('/^css\/build\/style\.[\w\d]{8}.css$/', $parsed);


	}

	function test_if_it_return_itself_with_css_as_the_working_directory()
	{
		$this->cachebuster = new CacheBuster('local');
		$parsed = $this->cachebuster->css('style.css');
		$expected = 'css/style.css';

		return $this->assertEquals(
			$parsed,
			$expected
		);
	}

	function test_if_it_return_itself_with_the_js_directory()
	{
		$this->cachebuster = new CacheBuster('local');

		$parsed = $this->cachebuster->js('main.js');
		$expected = 'js/main.js';

		return $this->assertEquals(
			$parsed,
			$expected
		);
	}

	function test_it_returns_a_random_string_of_eight_characters()
	{

		$parsed = $this->cachebuster->randomString();
		return $this->assertRegExp('/^[\w\d]{8}$/', $parsed);
	}

	function test_it_returns_the_current_build_name_if_it_exists()
	{
		$arrayOfMockedFilenames = ['.','..','.DS_store','admin.dshfshcd.css','style.dshfshcd.css'];
		$filename = 'style.css';
		$parsed = $this->cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertRegExp('/^style\.[\w\d]{8}\.css$/', $parsed);
	}

	function test_it_returns_the_current_build_name_if_it_exists_with_admin()
	{
		$arrayOfMockedFilenames = ['.','..','.DS_store','admin.dshfshcd.css','style.dshfshcd.css'];
		$filename = 'admin.css';
		$parsed = $this->cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertRegExp('/^admin\.[\w\d]{8}\.css$/', $parsed);
	}

	function test_it_returns_false_if_no_build_name_exists()
	{
		$arrayOfMockedFilenames = ['.','..','.DS_store'];
		$filename = 'style.css';
		$parsed = $this->cachebuster->getBuildFilename($arrayOfMockedFilenames, $filename);
		return $this->assertFalse($parsed);
	}

	function test_if_no_build_directory_exists_if_creates_a_new_one_and_sets_it_to_writable()
	{
		$cssDirectory = $_SERVER['PWD'].'/tests/public/builddir/';
		$createfile = fopen($cssDirectory.'style.css', 'w') or die('Cannot open file:  '.$my_file);

		$parsed = $this->cachebuster->fire('builddir/style.css', '', 'production', 'testing');
		$expected = 'builddir/style.css';

		unlink($cssDirectory.'style.css');
		unlink($cssDirectory.'/../'.$parsed);
		rmdir($cssDirectory."/build");

		return $this->assertRegExp('/^builddir\/build\/style\.[\w\d]{8}.css$/', $parsed);
	}



}
