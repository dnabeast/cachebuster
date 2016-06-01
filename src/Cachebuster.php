<?php

namespace DNABeast\CacheBuster;

class CacheBuster
{

	public $env;

	public function __construct($env = 'local')
	{
		$this->env = $env;
	}

	public function fire($fileName, $fileLocation=null)
	{

		// if is local then return unchanged
		// if the build file doesn't require replacing return the old build file
		// if the buildfile requires replacing, unlink the old file and replace with the new.


		$fileName = $fileLocation.$fileName;
		if ($this->env=='local') return $fileName;

		if ($this->env == 'testing') {
			$this->basePath = $_SERVER['PWD'].'/tests/public/';
		} else {
			$this->basePath = public_path().'/';
		}

		$this->file = pathinfo($fileName);

		$this->existsBuildFile();


		$buildDirectoryList = scandir($this->basePath.$this->file['dirname'].'/build');

		$buildFileName = $this->getBuildFileName($buildDirectoryList, $fileName);

		if($buildFileName){
			$buildFileName = $this->file['dirname'].'/build/'.$buildFileName;

			$this->cssFileIsOlderThanBuildFile = filemtime($this->basePath.$fileName)<filemtime($this->basePath.$buildFileName);
			$this->fileSizeEqual = (0==(filesize($this->basePath.$fileName)-filesize($this->basePath.$buildFileName)));

			if($this->cssFileIsOlderThanBuildFile&&$this->fileSizeEqual) {
				return $buildFileName;
			}

			unlink($this->basePath.$buildFileName);
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
		copy($this->basePath.$fileName, $this->basePath.$this->newBuildFileName);

		return $this->newBuildFileName;
	}


	public function css($fileName)
	{
		return $this->fire($fileName, 'css/');
	}

	public function js($fileName)
	{
		return $this->fire($fileName, 'js/');
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

    public function existsBuildFile(){
    	if (!file_exists($this->basePath.$this->file['dirname'].'/build')) {
    		mkdir($this->basePath.$this->file['dirname'].'/build');
    	}
    }
}
