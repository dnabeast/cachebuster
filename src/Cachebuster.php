<?php

namespace Typesaucer\CacheBuster;

class CacheBuster
{

	public function fire($fileName, $env = null)
	{

		// if is local then return unchanged
		// if the build file doesn't require replacing return the old build file
		// if the buildfile requires replacing, unlink the old file and replace with the new.

		$env!=null?:$env = getenv('APP_ENV');

		if ($env=='local'||$env=='testing') return $fileName;

		$this->file = pathinfo($fileName);

		$buildDirectoryList = scandir(public_path().'/'.$this->file['dirname'].'/build');

		$buildFileName = $this->getBuildFileName($buildDirectoryList, $fileName);

		if($buildFileName){
			$buildFileName = $this->file['dirname'].'/build/'.$buildFileName;

			$this->cssFileIsOlderThanBuildFile = filemtime($fileName)<filemtime($buildFileName);
			$this->fileSizeEqual = (0==(filesize($fileName)-filesize($buildFileName)));

			if($this->cssFileIsOlderThanBuildFile&&$this->fileSizeEqual) {
				return $buildFileName;
			}

			unlink($buildFileName);
		};


		$this->newBuildFileName =
			$this->file['dirname'].
			'/build/'.
			$this->file['filename'].
			'.'.
			$this->randomString().
			'.'.
			$this->file['extension'];

			// Potential case sensativity issue
		copy($fileName, $this->newBuildFileName);

		$firsttime = new FirstTime;
		$firsttime->putNewKey();

		return $this->newBuildFileName;
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
