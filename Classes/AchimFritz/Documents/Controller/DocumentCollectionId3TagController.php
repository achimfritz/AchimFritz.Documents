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
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentCollectionId3TagController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService
	 */
	protected $id3TagWriterService;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

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
	 * @param \AchimFritz\Documents\Domain\Model\Facet\DocumentCollection $documentCollection
	 * @return void
	 */
	public function createAction(DocumentCollection $documentCollection) {
		try {
			$cnt = $this->id3TagWriterService->tagDocumentCollection($documentCollection);
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage($cnt . ' Documents tagged with ' . $documentCollection->getCategory()->getPath());
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->handleException($e);
		} catch (\SolrClientException $e) {
			$this->handleException($e);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}

		if ($this->request->getReferringRequest() instanceof ActionRequest) {
			$this->redirectToRequest($this->request->getReferringRequest());
		} else {
			$this->redirect('index', 'Category', NULL, array('category' => $documentCollection->getCategory()));
		}
	}

}
