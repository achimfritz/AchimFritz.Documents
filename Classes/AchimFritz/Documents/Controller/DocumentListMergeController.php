<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentList;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentListMergeController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentListService
	 */
	protected $documentListService;

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
	public function initializeCreateAction() {
		parent::initializeCreateAction();
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();

		// category
		$propertyMappingConfiguration->forProperty('category');
		$sub = $propertyMappingConfiguration->getConfigurationFor('category');
		$sub->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$sub->allowAllProperties();

		// documentListItems
		$propertyMappingConfiguration->forProperty('documentListItems');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documentListItems');
		$sub->allowAllProperties();

		for ($i = 0; $i < 20; $i++) {
			$sub->forProperty($i)->allowAllProperties();
			$sub->forProperty($i)->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		}

	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function createAction(DocumentList $documentList) {
		$documentList = $this->documentListService->merge($documentList);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Documents updatet to ' . $documentList->getCategory()->getPath());
			$this->redirect('index', 'DocumentList', NULL, array('documentList' => $documentList));
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->addFlashMessage('Cannot merge with ' . $e->getMessage() . ' - ' . $e->getCode(), '', Message::SEVERITY_ERROR);
			$this->redirect('index', 'DocumentList');
		}
	}

}
