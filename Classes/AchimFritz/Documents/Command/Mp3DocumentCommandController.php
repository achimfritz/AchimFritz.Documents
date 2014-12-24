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
	 * @var \AchimFritz\Documents\Domain\Factory\Mp3DocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\Mp3Document\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

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