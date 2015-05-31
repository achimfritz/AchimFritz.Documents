<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocumentList AS DocumentList;

class ImageDocumentListController extends DocumentListController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentListRepository
	 */
	protected $documentListRepository;

}
