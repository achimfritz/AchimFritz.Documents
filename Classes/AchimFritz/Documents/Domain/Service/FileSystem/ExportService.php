<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument as Document;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * @Flow\Scope("singleton")
 */
class ExportService {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $fileSystemFactory;
	// TODO for mp3

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @param DocumentExport $documentExport
	 * @throws Exception
	 * @return integer
	 */
	public function export(DocumentExport $documentExport) {
		$directory = $this->createExportDirectory($documentExport);
		$cnt = 0;
		foreach ($documentExport->getDocuments() as $document) {
			$from = $this->createFromPath($document, $documentExport);
			$to = $this->createToPath($document, $documentExport, $cnt, $directory);
			$this->copyFile($from, $to);
			$cnt++;
		}
		return $cnt;
	}

	/**
	 * @param string $from 
	 * @param string $to 
	 * @throws Exception
	 * @return void
	 */
	protected function copyFile($from, $to) {
		if (@copy($from, $to) === FALSE) {
			throw new Exception('cannot copy document from ' . $from . ' to ' . $to , 1416762868);
		}
	}

	/**
	 * @param Document $document
	 * @param DocumentExport $documentExport
	 * @return string
	 */
	protected function createFromPath(Document $document, DocumentExport $documentExport) {
		$fileSystem = $this->fileSystemFactory->create($document);
		$from = $fileSystem->getAbsolutePath();
		if ($documentExport->getUseThumb() === TRUE) {
			// TODO for mp3
			$from = $this->pathService->replacePath($from, $this->settings['imageDocument']['mountPoint'], FLOW_PATH_WEB . $this->settings['imageDocument']['webPath']);
		}
		return $from;
	}

	/**
	 * @param string $directory 
	 * @param Document $document 
	 * @return string
	 */
	protected function createFullPathDirectory($directory, Document $document) {
		$directory = $directory . PathService::PATH_DELIMITER . $document->getDirectoryName();
		if (@mkdir($directory, 0777, TRUE) === FALSE) {
			throw new Exception('cannot create directory ' . $directory, 1416762870);
		}
		return $directory;
	}

	/**
	 * @param Document $document
	 * @param DocumentExport $documentExport 
	 * @param integer $cnt
	 * @param string $directory
	 * @return string
	 * @throws Exception
	 */
	protected function createToPath(Document $document, DocumentExport $documentExport, $cnt, $directory) {
		if ($documentExport->getUseFullPath() === TRUE) {
			$directory = $this->createFullPathDirectory($directory, $document);
		}
		$name = $document->getFileName();
		$prefix = '';
		switch ($documentExport->getSortByPrefix()) {
			case DocumentExport::SORT_BY_PREFIX_TIMESTAMP:
				$prefix = $document->getMDateTime()->format('u') . '_';
				break;
			case DocumentExport::SORT_BY_PREFIX_COUNTER:
				$prefix = sprintf('%04s', ($cnt + 1)). '_';
				break;
			default:
				break;
		}
		$to = $directory . PathService::PATH_DELIMITER . $prefix . $name;
		return $to;
	}

	/**
	 * @param DocumentExport $documentExport
	 * @throws Exception
	 * @return string
	 */
	protected function createExportDirectory(DocumentExport $documentExport) {
		$directory = $this->settings['export'] . PathService::PATH_DELIMITER . $documentExport->getName();
		if (@file_exists(directory) === TRUE) {
			throw new Exception('file exists ' . $directory, 1416762866);
		}
		if (@mkdir($directory) === FALSE) {
			throw new Exception('cannot create directory ' . $directory, 1416762867);
		}
		return $directory;
	}


}
