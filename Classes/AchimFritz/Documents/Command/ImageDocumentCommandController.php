<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentCommandController extends AbstractFileSystemDocumentCommandController {

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
	 * @var \AchimFritz\Documents\Domain\Factory\ImageIntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return FileSystemInterface::IMAGE_MOUNT_POINT;
	}

	/**
	 * @return string
	 */
	protected function getExtension() {
		return 'jpg';
	}
}
