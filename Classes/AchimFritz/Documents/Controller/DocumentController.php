<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Exception;

class DocumentController extends AbstractDocumentController {

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function createAction(Document $document) {
		$this->createDocument($document);
		$this->redirect('index', NULL, NULL, array('document' => $document));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function updateAction(Document $document) {
		$this->updateDocument($document);
		$this->redirect('index', NULL, NULL, array('document' => $document));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function deleteAction(Document $document) {
		$this->deleteDocument($document);
		$this->redirect('index');
	}

}

?>
