<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentListService extends DocumentListService {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 */
	protected $documentRepository;

}
