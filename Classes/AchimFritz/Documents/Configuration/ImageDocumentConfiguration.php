<?php
namespace AchimFritz\Documents\Configuration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentConfiguration extends FileSystemDocumentConfiguration {

	/**
	 * @var string
	 */
	protected $documentName = 'imageDocument';

	/**
	 * @return string
	 */
	public function getWebBigPath() {
		return $this->settings[$this->documentName]['webBigPath'];
	}

	/**
	 * @return string
	 */
	public function getWebPreviewPath() {
		return $this->settings[$this->documentName]['webPreviewPath'];
	}

	/**
	 * @return string
	 */
	public function getWebThumbPath() {
		return $this->settings[$this->documentName]['webThumbPath'];
	}

}
