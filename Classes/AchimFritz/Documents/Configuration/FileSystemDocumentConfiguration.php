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
class FileSystemDocumentConfiguration {

	/**
	 * @var string
	 */
	protected $documentName = 'fileSystemDocument';

	/**
	 * @var string
	 */
	protected $fileExtension = '';

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @return string
	 */
	public function getFileExtension() {
		return $this->fileExtension;
	}

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	public function getMountPoint() {
		return $this->settings[$this->documentName]['mountPoint'];
	}

	/**
	 * @return string
	 */
	public function getWebPath() {
		return $this->settings[$this->documentName]['webPath'];
	}
}
