<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Document;

class Mp3DocumentController extends DocumentController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Mp3Document $document
	 * @return void
	 */
	public function createAction(Document $document) {
		return parent::createAction($document);
	}

}
