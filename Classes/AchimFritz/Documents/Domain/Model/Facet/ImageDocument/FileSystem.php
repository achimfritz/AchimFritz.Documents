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
class FileSystem extends \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\FileSystem {

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

}
