<?php
namespace AchimFritz\Documents\Domain\Model\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document;
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
	 * @param Mp3Document $mp3Document 
	 * @return FileSystem
	 */
	public function create(Mp3Document $mp3Document) {
		$fileSystem = new FileSystem();
		$fileSystem->setRelativePath($mp3Document->getName());
		$fileSystem->setMountPoint($this->settings['mp3Document']['mountPoint']);
		$fileSystem->setWebPath($this->settings['mp3Document']['webPath'] . PathService::PATH_DELIMITER . $mp3Document->getName());
		return $fileSystem;
	}

}
