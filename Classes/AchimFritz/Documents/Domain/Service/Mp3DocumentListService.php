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
class Mp3DocumentListService extends DocumentListService {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 */
	protected $documentRepository;

}
