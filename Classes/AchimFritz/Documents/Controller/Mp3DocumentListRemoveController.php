<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3DocumentList as DocumentList;

class Mp3DocumentListRemoveController extends DocumentListRemoveController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\Mp3DocumentListService
	 */
	protected $documentListService;

}
