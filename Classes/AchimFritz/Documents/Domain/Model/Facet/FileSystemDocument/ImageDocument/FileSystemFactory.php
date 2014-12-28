<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Service\PathService;

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
		$fileSystem->setRelativePath($imageDocument->getName());
		$fileSystem->setWebPath($this->settings['imageDocument']['webPath'] . PathService::PATH_DELIMITER . $imageDocument->getName());
		$fileSystem->setWebThumbPath($this->settings['imageDocument']['webThumbPath'] . PathService::PATH_DELIMITER . $imageDocument->getName());
		$fileSystem->setWebBigPath($this->settings['imageDocument']['webBigPath'] . PathService::PATH_DELIMITER . $imageDocument->getName());
		$fileSystem->setWebPreviewPath($this->settings['imageDocument']['webPreviewPath'] . PathService::PATH_DELIMITER . $imageDocument->getName());
		return $fileSystem;
	}

}
