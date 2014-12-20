<?php
namespace AchimFritz\Documents\Domain\Model\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemFactory {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @param ImageDocument $imageDocument 
	 * @return FileSystem
	 */
	public function create(ImageDocument $imageDocument) {
		$fileSystem = new FileSystem();
		$fileSystem->setMountPoint($this->settings['imageDocument']['mountPoint']);
		$fileSystem->setWebPath($this->settings['imageDocument']['webPath']);
		$fileSystem->setWebThumbPath($this->settings['imageDocument']['webThumbPath']);
		$fileSystem->setWebBigPath($this->settings['imageDocument']['webBigPath']);
		$fileSystem->setWebPreviewPath($this->settings['imageDocument']['webPreviewPath']);
		return $fileSystem;
	}

}
