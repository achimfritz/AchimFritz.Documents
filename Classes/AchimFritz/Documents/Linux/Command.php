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
	 * @return array
	 */
	public function burnWavCd($directoryName) {
		$cmd = 'wodim dev=/dev/sr0 fs=4096k driveropts=burnproof -v -useinfo speed=24 -dao -eject -pad -audio ' . $directoryName . '.wav';
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $mp3File
	 * @param string $wavFile
	 * @return array
	 * @throws Exception
	 */
	public function mp3ToWav($mp3File, $wavFile) {
		if (file_exists($mp3File) === FALSE) {
			throw new Exception('no such file ' . $mp3File, 1454253523);
		}
		if (file_exists($wavFile) === TRUE) {
			throw new Exception('wav file exists ' . $wavFile, 1454253524);
		}
		$cmd = 'mpg123 -y -w' . $mp3File . ' ' . $wavFile;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $wavFile
	 * @param string $mp3File
	 * @return array
	 * @throws Exception
	 */
	public function wavToMp3($wavFile, $mp3File) {
		if (file_exists($wavFile) === FALSE) {
			throw new Exception('no such file ' . $mp3File, 1454253525);
		}
		if (file_exists($mp3File) === TRUE) {
			throw new Exception('mp3 file exists ' . $mp3File, 1454253526);
		}
		$cmd = 'ffmpeg -i ' . $wavFile . ' -acodec mp3 -ac 2 -ab 320k ' . $mp3File;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $directoryName 
	 * @return array
	 */
	public function burnDataCd($directoryName) {
		$cmd = 'genisoimage -o /tmp/image.iso -f -J -r -l ' . $directoryName;
		$this->executeCommand($cmd);
		$cmd = 'wodim dev=/dev/sr0 fs=4096k -ignsize driveropts=burnproof -v -useinfo speed=12 -dao -eject -pad -data /tmp/image.iso';
		$this->executeCommand($cmd);
	}

	/**
	 * @param string $name
	 * @param string $timestampFile
	 * @return string
	 * @throws Exception
	 */
	public function getImageTimestampFromTimestampFile($name, $timestampFile) {
		if (file_exists($timestampFile) === FALSE) {
			throw new Exception('no such file ' . $timestampFile, 1435403125);
		}
		$cmd = 'grep "' . $name . '" ' . $timestampFile . ' |awk -F "|" {\'print $1\'}';
		$res = $this->executeCommand($cmd);
		return $res[0];
	}

	/**
	 * @param string $file 
	 * @return intger
	 * @throws Exception
	 */
	public function getImageOrientationFromGeeqieMetaDataFile($file) {
		if (file_exists($file) === FALSE) {
			throw new Exception('no such file ' . $file, 1435403124);
		}
		$cmd = 'grep "tiff:Orientation" ' . $file . ' |awk -F "\"" {\'print $2\'}';
		$res = $this->executeCommand($cmd);
		return (int)$res[0];
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
		$cmd = 'exif -m ' . $file;
		return $this->executeCommand($cmd);
	}

	/**
	 * @param string $file 
	 * @param string $format
	 * @return array
	 * @throws Exception
	 */
	public function readId3Tags($file, $format = '') {
		if (file_exists($file) === FALSE) {
			throw new Exception('no such file ' . $file, 1419089786);
		}
		if ($format !== '') {
			//$format = 'rfc822';
			//$format = 'jep-118';
			$cmd = 'eyeD3 --no-color --' . $format . ' ' . $file;
		} else {
			$cmd = 'eyeD3 --no-color ' . $file;
		}
		return $this->executeCommand($cmd);
	}

	/**
	 * @param $file
	 * @return void
	 * @throws Exception
	 */
	public function removeId3Tags($file) {
		if (file_exists($file) === FALSE || is_writable($file) === FALSE) {
			throw new Exception('no such file or not writeable ' . $file, 1437576178);
		}
		$cmd = 'eyeD3 --remove-v1 ' . $file;
		$this->executeCommand($cmd);
		$cmd = 'eyeD3 --remove-v2 ' . $file;
		$this->executeCommand($cmd);
	}

	/**
	 * @param string $file 
	 * @return void
	 * @throws Exception
	 */
	public function writeId3Tag($file, $tagName, $tagValue) {
		if (file_exists($file) === FALSE || is_writable($file) === FALSE) {
			throw new Exception('no such file or not writeable ' . $file, 1419089788);
		}
		$cmd = 'eyeD3 --' . $tagName . '="' . $tagValue . '" ' . $file;
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
