<?php
namespace AchimFritz\Documents\Linux;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Command {

	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function burnWavCd($directoryName) {
		$cmd = 'wodim dev=/dev/sr0 fs=4096k driveropts=burnproof -v -useinfo speed=24 -dao -eject -pad -audio ' . $directoryName . '.wav';
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function burnDataCd($directoryName) {
		$cmd = 'genisoimage -o /tmp/image.iso -f -J -r -l ' . $directoryName;
		$this->executeCommand($cmd);
		$cmd = 'wodim dev=/dev/sr0 fs=4096k -ignsize driveropts=burnproof -v -useinfo speed=12 -dao -eject -pad -data /tmp/image.iso';
		$this->executeCommand($cmd);
	}

	/**
	 * @param string $file 
	 * @return array
	 * @throws Exception
	 */
	public function getExifData($file) {
		if (file_exists($file) === FALSE) {
			throw new Exception('no such file ' . $file, 1419089786);
		}
		$cmd = 'exif -i ' . $file;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $file 
	 * @return array
	 * @throws Exception
	 */
	public function readId3Tags($file) {
		if (file_exists($file) === FALSE) {
			throw new Exception('no such file ' . $file, 1419089786);
		}
		$cmd = 'eyeD3 --no-color --rfc822 ' . $file;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $cmd
	 * @return array
	 * @throws Exception
	 */
	public function executeCommand($cmd) {
		exec($cmd, $res, $ret);
		if ($ret !== 0) {
			throw new Exception('cannot execute ' . $cmd, 1418925116);
		}
		return $res;
	}

	/**
	 * @param string $directory 
	 * @return array
	 * @throws Exception
	 */
	public function zipDirectory($directory) {
		if (@file_exists($directory) === FALSE) {
			throw new Exception('directory not exists ' . $directory, 1421345841);
		}
		$splFileInfo = new \SplFileInfo($directory);
		$parent = $splFileInfo->getPathInfo()->getRealPath();
		$name = $splFileInfo->getBasename();
		$cmd = 'cd ' . $parent . ' && zip -r ' . $name . '.zip ' . $name;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $directory 
	 * @return array
	 * @throws Exception
	 */
	public function rmDirRecursive($directory) {
		$cmd = 'rm -r ' . $directory;
		return $this->executeCommand($cmd);
	}
}
