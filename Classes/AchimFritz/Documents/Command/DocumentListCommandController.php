<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\Cagegory;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DocumentListExportService
	 * @Flow\Inject
	 */
	protected $exportService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentListService
	 */
	protected $documentListService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;

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
	 * listCommand()
	 *
	 * @return void
	 */
	public function listCommand() {
		$documentLists = $this->documentListRepository->findAll();
		if (count($documentLists) === 0) {
			$this->outputLine('WARNING: no documentLists found');
		}
		foreach ($documentLists AS $list) {
			$this->outputLine($list->getCategory()->getPath());
		}
	}

	/**
	 * showCommand($path = 'af/list/test')
	 *
	 * @param string $path
	 * @return void
	 */
	public function showCommand($path = 'af/list/test') {
		$documentList = $this->documentListRepository->findOneByCategoryPath($path);
		if ($documentList instanceof DocumentList === FALSE) {
			$this->outputLine('WARNING: documentList not found with category.path ' . $path);
			$this->quit();
		}
		$cnt = 1;
		foreach ($documentList->getDocumentListItems() AS $item) {
			$pre = sprintf('%02s', $cnt);
			$id3Tag = $this->id3TagFactory->create($item->getDocument());
			$this->outputLine($pre. ' ' . $id3Tag->getArtist() . ' - ' . $id3Tag->getTitle());
			$cnt++;
		}
	}

	/**
	 * exportCommand($path = 'af/list/test')
	 *
	 * @param string $path
	 * @return void
	 */
	public function exportCommand($path = 'af/list/test') {
		$documentList = $this->documentListRepository->findOneByCategoryPath($path);
		if ($documentList instanceof DocumentList === FALSE) {
			$this->outputLine('WARNING: documentList not found with category.path ' . $path);
			$this->quit();
		}
		try {
			$cnt = $this->exportService->export($documentList);
			$this->outputLine('SUCCESS: ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			$this->outputLine('ERROR: cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * directoryToListCommand($directory, $path)
	 *
	 * @param string $directory 
	 * @param string $path 
	 * @return void
	 */
	public function directoryToListCommand($directory, $path) {
		try {
			$documentList = $this->documentListService->directoryToList($directory, $path);
			try {
				$this->documentPersistenceManager->persistAll();
				$this->outputLine('SUCCESS: saved ' . count($documentList->getDocumentListItems()) . ' documents');
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->outputLine('ERROR: ' . $e->getMessage());
			}
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->outputLine('ERROR: with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
