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
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\FileSystemDocumentFactory
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
	abstract protected function getMountPoint();

	/**
	 * @param string $directory
	 * @param boolean $update
	 * @throws \AchimFritz\Documents\Domain\Service\Exception
	 * @return integer
	 */
	public function indexDirectory($directory, $update = FALSE) {
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directory;
		$cnt = 0;
		try {
			$fileNames = $this->directoryService->getFileNamesInDirectory($path, $this->extension);
			foreach ($fileNames AS $fileName) {
				$document = $this->documentRepository->findOneByName($directory . PathService::PATH_DELIMITER . $fileName);
				if ($document instanceof Document === FALSE) {
					$cnt++;
					$document = $this->documentFactory->create($directory . PathService::PATH_DELIMITER . $fileName, $this->getMountPoint());
					$this->documentRepository->add($document);
				} elseif ($update === TRUE) {
					$dummy = $this->documentFactory->create($directory . PathService::PATH_DELIMITER . $fileName, $this->getMountPoint());
					$document->setMDateTime($dummy->getMDateTime());
					$document->setFileHash($dummy->getFileHash());
					$this->documentRepository->update($document);
					$cnt++;
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
