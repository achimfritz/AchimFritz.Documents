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
class ImageDocumentListCommandController extends DocumentListCommandController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\ImageDocumentListService
	 */
	protected $documentListService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Model\ImageDocumentListFactory
	 */
	protected $documentListFactory;

}
