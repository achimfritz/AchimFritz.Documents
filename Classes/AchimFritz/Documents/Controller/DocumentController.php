<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Document;

class DocumentController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

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
	public function createAction(Document $document) {
		$this->documentRepository->add($document);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Created a new document.');
			$this->redirect('index', NULL, NULL, array('document' => $document));
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->addFlashMessage('Cannot Create a new document with ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
			$this->redirect('index');
		}
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function updateAction(Document $document) {
		$this->documentRepository->update($document);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Updated the document.');
			$this->redirect('index', NULL, NULL, array('document' => $document));
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->addFlashMessage('Cannot Update the document with ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
			$this->redirect('index');
		}
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function deleteAction(Document $document) {
		$this->documentRepository->remove($document);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Deleted a document.');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->addFlashMessage('Cannot Delete a document with ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
		}
		$this->redirect('index');
	}

}
