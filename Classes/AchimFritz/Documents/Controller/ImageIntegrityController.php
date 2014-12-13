<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\FileSystemInterface;

class ImageIntegrityController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'directory';

	/**
	 * @return void
	 */
	public function listAction() {
		$path = FileSystemInterface::IMAGE_MOUNT_POINT;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		$cnt = 0;
		$directories = array();
		foreach ($directoryIterator AS $outerFileInfo) {
			if ($outerFileInfo->isDir() === TRUE) { 
				$innerIterator = new \DirectoryIterator($outerFileInfo->getRealpath());
				$cnt = 0;
				foreach ($innerIterator AS $fileInfo) {
					if ($fileInfo->getExtension() === 'jpg') {
						$cnt++;
					}
				}
				$directories[$outerFileInfo->getBasename()] = $cnt;
			}
		}
		$this->view->assign('directories', $directories);
	}

	/**
	 * @param string $directory
	 * @return void
	 */
	public function showAction($directory) {
		$this->view->assign('directory', $directory);
	}

}
