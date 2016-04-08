<?php

namespace Typesaucer\CacheBuster;

class CacheBuster
{

	public function fire($fileName, $fileLocation = null, $env = null)
	{

		// if is local then return unchanged
		// if the build file doesn't require replacing return the old build file
		// if the buildfile requires replacing, unlink the old file and replace with the new.

		$env!=null?:$env = getenv('APP_ENV');

		if (getenv('APP_ENV') == 'testing') {
			$basePath = $_SERVER['PWD'].'/tests/public/';
		} else {
			$basePath = public_path().'/';
		}

		$fileName = $fileLocation. $fileName;

		if ($env=='local') return $fileName;

		$this->file = pathinfo($fileName);

		if (!file_exists($basePath.$this->file['dirname'].'/build')) {
			mkdir($basePath.$this->file['dirname'].'/build');
		}

		$buildDirectoryList = scandir($basePath.$this->file['dirname'].'/build');

		$buildFileName = $this->getBuildFileName($buildDirectoryList, $fileName);

		if($buildFileName){
			$buildFileName = $this->file['dirname'].'/build/'.$buildFileName;

			$this->cssFileIsOlderThanBuildFile = filemtime($basePath.$fileName)<filemtime($basePath.$buildFileName);
			$this->fileSizeEqual = (0==(filesize($basePath.$fileName)-filesize($basePath.$buildFileName)));

			if($this->cssFileIsOlderThanBuildFile&&$this->fileSizeEqual) {
				return $buildFileName;
			}

			unlink($basePath.$buildFileName);
		};


		$this->newBuildFileName =
			$this->file['dirname'].
			'/build/'.
			$this->file['filename'].
			'.'.
			$this->randomString().
			'.'.
			$this->file['extension'];

			// Potential case sensitivity issue
		copy($basePath.$fileName, $basePath.$this->newBuildFileName);

		return $this->newBuildFileName;
	}



	public function css($fileName, $env = null)
	{
		return $this->fire($fileName, 'css/', $env);
	}

	public function js($fileName, $env = null)
	{
		return $this->fire($fileName, 'js/', $env);
	}


	public function randomString()
	{
		$randomString= '';
		for ($i = 0; $i<8; $i++) {
			$randomString .= chr(rand(97,122));
		}
		return $randomString;
	}

    public function getBuildFileName($arrayOfFilenames, $filename)
    {
    	$filename = preg_replace('/.*\/(.*)/', '$1', $filename);
    	foreach($arrayOfFilenames as $buildName)
    	{
				if (substr($buildName, 0 , 3)==substr($filename, 0, 3)){
	    			return $buildName;
				}
    	}
		return false;

    }
}
