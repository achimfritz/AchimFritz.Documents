<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class DirectoryService {

	/**
	 * @param string $path 
	 * @param string $extension 
	 * @return string
	 * @throws Exception
	 */
	public function getFileNamesInDirectory($path, $extension) {
		$fileNames = array();
		$splFileInfos = $this->getSplFileInfosInDirectory($path, $extension);
		foreach ($splFileInfos AS $splFileInfo) {
			$fileNames[] = $splFileInfo->getBasename();
		}
		return $fileNames;
		
	}

	/**
	 * @param string $path 
	 * @param string $extension 
	 * @return integer
	 * @throws Exception
	 */
	public function getCountOfFilesByExtension($path, $extension) {
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1419357137);
		}
		$cnt = 0;
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->getExtension() === $extension) {
				$cnt++;
			}
		}
		return $cnt;
	}

	/**
	 * @param string $path 
	 * @param string $extension 
	 * @return array<\SplFileInfo>
	 * @throws Exception
	 */
	public function getSplFileInfosInDirectory($path, $extension) {
		$splFileInfos = [];
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1419357135);
		}
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->getExtension() === $extension) {
				$splFileInfos[$fileInfo->getBasename()] = clone($fileInfo);
			}
		}
		ksort($splFileInfos);
		return $splFileInfos;
	}

	/**
	 * @param string $path 
	 * @return array<\SplFileInfo>
	 * @throws Exception
	 */
	public function getDirectoriesInDirectory($path) {
		$splFileInfos = [];
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			throw new Exception('cannot create DirectoryIterator with path ' . $path, 1419357136);
		}
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->isDir() === TRUE && $fileInfo->isDot() === FALSE) {
				$splFileInfos[] = clone($fileInfo);
				
			}
		}
		return $splFileInfos;

	}


}
