<?php
namespace AchimFritz\Documents\Domain\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\FileSystemInterface;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("prototype")
 */
class FileSystem {

	/**
	 * @var string
	 */
	protected $relativePath;

	/**
	 * @param string $relativPath 
	 * @return void
	 */
	public function __construct($relativPath) {
		$this->relativPath = $relativPath;
	}


	/**
	 * @return void
	 */
	public function getMountPoint() {
		return FileSystemInterface::IMAGE_MOUNT_POINT;
	}

	/**
	 * @return string webThumbPath
	 */
	public function getWebThumbPath() {
		return FileSystemInterface::IMAGE_THUMB_THUMB . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string webPreviewPath
	 */
	public function getWebPreviewPath() {
		return FileSystemInterface::IMAGE_THUMB_PREVIEW . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string webBigPath
	 */
	public function getWebBigPath() {
		return FileSystemInterface::IMAGE_THUMB_BIG . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string webPath
	 */
	public function getWebPath() {
		return FileSystemInterface::IMAGE_THUMB_WEB . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return integer
	 */
	public function getYear() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[0];
	}

	/**
	 * @return integer
	 */
	public function getMonth() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[1];
	}

	/**
	 * @return integer
	 */
	public function getDay() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[2];
	}


}
