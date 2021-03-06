<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentListController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;


	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentList';

	/**
	 * @return void
	 */
	public function initializeUpdateAction() {
		parent::initializeUpdateAction();
		if ($this->request->hasArgument($this->resourceArgumentName)) {
			$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();
			$argument = $this->request->getArgument($this->resourceArgumentName);
			if (is_array($argument['documentListItems']) === TRUE) {
				$propertyMappingConfiguration->forProperty('documentListItems');
				$sub = $propertyMappingConfiguration->getConfigurationFor('documentListItems');
				$sub->allowAllProperties();
				for($i = 0; $i < count($argument['documentListItems']); $i++) {
					$sub->forProperty($i)->allowAllProperties();
					$sub->forProperty($i)->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('documentLists', $this->documentListRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function showAction(DocumentList $documentList) {
		$this->view->assign('documentList', $documentList);
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function updateAction(DocumentList $documentList) {
		$this->documentListRepository->update($documentList);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Updated a documentList.');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index');
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function deleteAction(DocumentList $documentList) {
		$this->documentListRepository->remove($documentList);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Deleted a documentList.');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index');
	}
}
