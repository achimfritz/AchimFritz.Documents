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
	 * @var \AchimFritz\Documents\Domain\Model\FileSystemDocumentListFactory
	 */
	protected $documentListFactory;

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
	 * list
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
	 * show --path=af/list/test
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
		foreach ($documentList->getDocumentListItems() AS $item) {
			$this->outputLine($item->getDocument()->getAbsolutePath());
		}
	}

	/**
	 * export --path=af/list/test
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
	 * createfromfile --file=/mp3/db/lucky/m3u/doris_2014.m3u --path=lucky/m3u/doris_2014
	 *
	 * @param string $file
	 * @param string $path
	 * @return void
	 */
	public function createFromFileCommand($file = '/mp3/db/lucky/m3u/doris_2014.m3u', $path = 'lucky/m3u/doris_2014') {
		try {
			$documentList = $this->documentListFactory->createFromFile($file, $path);
			try {
				$this->documentListService->merge($documentList);
				try {
					$this->documentPersistenceManager->persistAll();
					$this->outputLine('SUCCESS: saved ' . count($documentList->getDocumentListItems()) . ' documents');
				} catch (\AchimFritz\Documents\Persistence\Exception $e) {
					$this->outputLine('ERROR: ' . $e->getMessage());
				}
			} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
				$this->outputLine('ERROR: cannot merge documentList with ' . $e->getMessage() . ' - ' . $e->getCode());
			}
		} catch (\AchimFritz\Documents\Domain\Model\Exception $e) {
			$this->outputLine('ERROR: cannot create documentList with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * createfromdirectory --directory=/bilder/save_main/2007_06_23_hochzeit_claudi_mario_diashow_bildershow --path=categories/diashow/hochzeit_claudi_mario/show
	 * 
	 * @param string $directory 
	 * @param string $path 
	 * @return void
	 */
	public function createFromDirectoryCommand($directory = '/bilder/save_main/2007_06_23_hochzeit_claudi_mario_diashow_bildershow', $path='categories/diashow/hochzeit_claudi_mario/show') {
		try {
			$documentList = $this->documentListFactory->createFromDirectory($directory, $path);
			try {
				$this->documentListService->merge($documentList);
				try {
					$this->documentPersistenceManager->persistAll();
					$this->outputLine('SUCCESS: saved ' . count($documentList->getDocumentListItems()) . ' documents');
				} catch (\AchimFritz\Documents\Persistence\Exception $e) {
					$this->outputLine('ERROR: ' . $e->getMessage());
				}
			} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
				$this->outputLine('ERROR: cannot merge documentList with ' . $e->getMessage() . ' - ' . $e->getCode());
			}
		} catch (\AchimFritz\Documents\Domain\Model\Exception $e) {
			$this->outputLine('ERROR: cannot create documentList with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
