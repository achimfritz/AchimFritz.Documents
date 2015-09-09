<?php
namespace AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class IntegrityFactory extends \AchimFritz\Documents\Domain\FileSystem\Factory\IntegrityFactory {

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3DocumentConfiguration;

   /**
    * @Flow\Inject
    * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
    */
   protected $documentRepository;

	/**
	 * @return Integrity
	 * @throws Exception
	 */
	public function createIntegrity($directory) {
		throw new AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Exception('not implemented', 1432389131);
	}

	/**
	 * @param string $path 
	 * @return array<\SplFileInfo>
	 * @throws \AchimFritz\Documents\Domain\FileSystem\Service\Exception
	 */
	protected function getDocumentDirectories($path) {
		$directories = $this->directoryService->getDirectoriesInDirectoryRecursive($path, 3);
		return $directories;
	}

	/**
	 * @param \SplFileInfo $fileInfo 
	 * @return string
	 */
	protected function getIntegrityName(\SplFileInfo $fileInfo) {
		return str_replace($this->getConfiguration()->getMountPoint() . PathService::PATH_DELIMITER, '', $fileInfo->getRealPath());
	}

	/**
	 * @return \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3DocumentConfiguration;
	}
}
