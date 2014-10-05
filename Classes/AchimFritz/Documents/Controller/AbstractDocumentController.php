<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

abstract class AbstractDocumentController extends AbstractActionController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentsPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'document';

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('documents', $this->documentRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function showAction(Document $document) {
		$this->view->assign('document', $document);
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	protected function createDocument(Document $document) {
		try {
			$this->documentRepository->add($document);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('document created');
		} catch (\Exception $e) {
			$this->addErrorMessage('cannot create document');
			$this->handleException($e);
		}
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function deleteDocument(Document $document) {
		try {
			$this->documentRepository->remove($document);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('document deleted');
		} catch (\Exception $e) {
			$this->addErrorMessage('cannot delete document');
			$this->handleException($e);
		}
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	protected function updateDocument(Document $document) {
		try {
			$this->documentRepository->update($document);
			$this->documentsPersistenceManager->persistAll();
			$this->addOkMessage('document updated');
		} catch (\Exception $e) {
			$this->addErrorMessage('cannot update document');
			$this->handleException($e);
		}
	}

}

?>
