<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Facet\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentCollectionRemoveController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentCollectionService
	 */
	protected $documentCollectionService;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentCollection';

	/**
	 * @return void
	 */
	public function initializeCreateAction() {
		parent::initializeCreateAction();
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();

		// category
		$propertyMappingConfiguration->forProperty('category');
		$sub = $propertyMappingConfiguration->getConfigurationFor('category');
		$sub->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$sub->allowAllProperties();

		// documents
		$propertyMappingConfiguration->forProperty('documents');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documents');
		$sub->allowAllProperties();
	}

	/**
	 * @return void
	 */
	public function initializeDeleteAction() {
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->forProperty('documents');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documents');
		$sub->allowAllProperties();
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Facet\DocumentCollection $documentCollection
	 * @return void
	 */
	public function createAction(DocumentCollection $documentCollection) {
		$cnt = $this->documentCollectionService->remove($documentCollection);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage($cnt . ' Documents removed from ' . $documentCollection->getCategory()->getPath());
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
		if ($this->request->getReferringRequest() instanceof ActionRequest) {
			$this->redirectToRequest($this->request->getReferringRequest());
		} else {
			$this->redirect('list', 'Category', NULL, array('category' => $documentCollection->getCategory()));
		}
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Facet\DocumentCollection $documentCollection
	 * @return void
	 */
	public function deleteAction(DocumentCollection $documentCollection) {
		try {
			$cnt = $this->documentCollectionService->removeAndDeleteFiles($documentCollection);
			$this->addFlashMessage($cnt . ' Documents removed from FS');
			if ($this->request->getReferringRequest() instanceof ActionRequest) {
				$this->redirectToRequest($this->request->getReferringRequest());
			} else {
				$this->redirect('list', 'Document');
			}
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->handleException($e);
			if ($this->request->getReferringRequest() instanceof ActionRequest) {
				$this->redirectToRequest($this->request->getReferringRequest());
			} else {
				$this->redirect('list', 'Document');
			}
		}
	}

}
