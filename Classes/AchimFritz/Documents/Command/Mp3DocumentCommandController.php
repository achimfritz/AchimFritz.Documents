<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentCommandController extends AbstractFileSystemDocumentCommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;
		
	/**
	 * @var \AchimFritz\Documents\Domain\Model\Mp3DocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\Mp3IndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\ExportService
	 * @Flow\Inject
	 */
	protected $exportService;


	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return $this->settings['mp3Document']['mountPoint'];
	}

	/**
	 * @return string
	 */
	protected function getExtension() {
		return 'mp3';
	}
}
