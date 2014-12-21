<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("prototype")
 */
class FileSystem {

	/**
	 * @var string
	 */
	protected $mountPoint;

	/**
	 * @var string
	 */
	protected $webPath;

	/**
	 * @var string
	 */
	protected $relativePath;

	/**
	 * @var \SplFileInfo
	 */
	protected $splFileInfo;

	/**
	 * @return string
	 */
	public function getAbsolutePath() {
		return $this->getMountPoint() . PathService::PATH_DELIMITER . $this->getRelativePath();
	}

	/**
	 * @return string relativePath
	 */
	public function getRelativePath() {
		return $this->relativePath;
	}

	/**
	 * @param string $relativePath
	 * @return void
	 */
	public function setRelativePath($relativePath) {
		$this->relativePath = $relativePath;
	} 


	/**
	 * @return string
	 */
	public function getMountPoint() {
		return $this->mountPoint;
	}

	/**
	 * @param string $mountPoint
	 * @return void
	 */
	public function setMountPoint($mountPoint) {
		$this->mountPoint = $mountPoint;
	}

	/**
	 * @return string
	 */
	public function getWebPath() {
		return $this->webPath;
	}

	/**
	 * @param string $webPath
	 * @return void
	 */
	public function setWebPath($webPath) {
		$this->webPath = $webPath;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->getSplFileInfo()->getBasename();
	}

	/**
	 * @return string
	 */
	public function getDirectoryName() {
		return $this->getSplFileInfo()->getPathInfo()->getBasename();
	}

	/**
	 * getSplFileInfo 
	 * 
	 * @return \SplFileInfo
	 */
	public function getSplFileInfo() {
		if (!$this->splFileInfo instanceof \SplFileInfo) {
			$this->splFileInfo = new \SplFileInfo($this->getAbsolutePath());
		}
		return $this->splFileInfo;
	}

}
