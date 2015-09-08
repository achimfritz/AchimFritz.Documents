<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument as Document;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * @Flow\Scope("singleton")
 */
abstract class AbstractExportService {

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
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
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
	 * @param string $directory 
	 * @return void
	 * @throws Exception
	 */
	protected function zipDirectory($directory) {
		if (@file_exists($directory . '.zip') === TRUE) {
			if (@unlink($directory . '.zip') === FALSE) {
				throw new Exception('cannot rm existing file ' . $directory . '.zip' , 1421345343);
			}
		}
		try {
			$this->linuxCommand->zipDirectory($directory);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			throw new Exception('cannot zip directory ' . $directory, 1421343749);
		}
	}


	/**
	 * @param string $directory 
	 * @param Document $document 
	 * @return string
	 */
	protected function createFullPathDirectory($directory, Document $document) {
		$directory = $directory . PathService::PATH_DELIMITER . $document->getDirectoryName();
		if (@file_exists($directory)) {
			return $directory;
		}
		if (@mkdir($directory, 0777, TRUE) === FALSE) {
			throw new Exception('cannot create directory ' . $directory, 1416762870);
		}
		return $directory;
	}

	/**
	 * @param string $name
	 * @throws Exception
	 * @return string
	 */
	protected function createExportDirectory($name) {
		$directory = PathService::TEMP_FOLDER . PathService::PATH_DELIMITER . $name;
		if (@file_exists($directory) === TRUE) {
			try {
				$this->linuxCommand->rmDirRecursive($directory);
			} catch (\AchimFritz\Documents\Linux\Exception $e) {
				throw new Exception('cannot rm ' . $directory, 1416762866);
			}
		}
		if (@mkdir($directory) === FALSE) {
			throw new Exception('cannot create directory ' . $directory, 1416762867);
		}
		return $directory;
	}


}
