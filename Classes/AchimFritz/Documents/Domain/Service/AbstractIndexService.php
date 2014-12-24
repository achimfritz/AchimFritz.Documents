<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * @Flow\Scope("singleton")
 */
abstract class AbstractIndexService {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var string
	 */
	protected $extension = '';

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\ImageDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return $this->settings['imageDocument']['mountPoint'];
	}

	/**
	 * @param string $directory
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 * @return integer
	 */
	public function indexDirectory($directory) {
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directory;
		$cnt = 0;
		try {
			$fileNames = $this->directoryService->getFileNamesInDirectory($path, $this->extension);
			foreach ($fileNames AS $fileName) {
				$document = $this->documentRepository->findOneByName($directory . PathService::PATH_DELIMITER . $fileName);
				if ($document instanceof Document === FALSE) {
					$cnt++;
					$document = $this->documentFactory->create($directory . PathService::PATH_DELIMITER . $fileName);
					$this->documentRepository->add($document);
				}
			}
		} catch (\AchimFritz\Documents\Domain\Service\Filesystem\Exception $e) {
			throw new Exception('got Filesystem Exception with ' . $e->getMessage() . ' - ' . $e->getCode(), 1419428823);
		}
      try {
         $this->documentPersistenceManager->persistAll();
      } catch (\AchimFritz\Documents\Persistence\Exception $e) {
			throw new Exception('got Persistence Exception with ' . $e->getMessage() . ' - ' . $e->getCode(), 1419428824);
      }	
		return $cnt;
	}

}
