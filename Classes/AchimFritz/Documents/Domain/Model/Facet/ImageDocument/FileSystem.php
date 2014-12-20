<?php
namespace AchimFritz\Documents\Domain\Model\Facet\ImageDocument;

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
	protected $webBigPath;

	/**
	 * @var string
	 */
	protected $webPreviewPath;

	/**
	 * @var string
	 */
	protected $webThumbPath;

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
	public function getWebBigPath() {
		return $this->webBigPath;
	}

	/**
	 * @param string $webBigPath
	 * @return void
	 */
	public function setWebBigPath($webBigPath) {
		$this->webBigPath = $webBigPath;
	}

	/**
	 * @return string
	 */
	public function getWebPreviewPath() {
		return $this->webPreviewPath;
	}

	/**
	 * @param string $webPreviewPath
	 * @return void
	 */
	public function setWebPreviewPath($webPreviewPath) {
		$this->webPreviewPath = $webPreviewPath;
	}

	/**
	 * @return string
	 */
	public function getWebThumbPath() {
		return $this->webThumbPath;
	}

	/**
	 * @param string $webThumbPath
	 * @return void
	 */
	public function setWebThumbPath($webThumbPath) {
		$this->webThumbPath = $webThumbPath;
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
