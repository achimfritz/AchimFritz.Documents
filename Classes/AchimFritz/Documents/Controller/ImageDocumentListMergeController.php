<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocumentList as DocumentList;

class ImageDocumentListMergeController extends DocumentListMergeController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\ImageDocumentListService
	 */
	protected $documentListService;

}
