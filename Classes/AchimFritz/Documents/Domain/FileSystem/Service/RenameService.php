<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class RenameService {

	/**
	 * @param $path
	 * @return string $renamed
	 * @throws Exception
	 */
	public function rename($path) {
		if (@file_exists($path) === FALSE) {
			throw new AchimFritz\Documents\Domain\Facet\Exception('file not exists ' . $path, 1418667068);
		}
		$renamed = $this->getCleanPath($path);
		if (@rename($path, $renamed) === FALSE) {
			throw new AchimFritz\Documents\Domain\Facet\Exception('cannot rename ' . $path . ' to ' . $renamed, 1418667069);
		}
		return $renamed;
	}

	/**
	 * @param string $name
	 * @return string $renamed
	 */
	protected function getCleanName($name) {
		$splFileInfo = new \SplFileInfo($name);
		$ext = $splFileInfo->getExtension();
		$name = $splFileInfo->getBasename('.' . $ext);
		$parts = explode(' ',trim($name));
		$newParts = array();
		foreach($parts AS $part){
			$newParts[] = preg_replace('/[^A-Za-z0-9_.-]*/', '', trim(ucfirst($part)));
		}
		$renamed = implode('',$newParts) . '.' . strtolower($ext);
		return $renamed;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function getCleanPath($path) {
		$parts = explode(PathService::PATH_DELIMITER, $path);
		$name = array_pop($parts);
		$renamed = $this->getCleanName($name);
		return implode(PathService::PATH_DELIMITER, $parts) . PathService::PATH_DELIMITER . $renamed;
	}


}
