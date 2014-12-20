<?php
namespace AchimFritz\Documents\Domain\Model\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

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

}
