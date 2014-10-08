<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Category;

class DocumentCollectionController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentCollection';

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return void
	 */
	public function createAction(DocumentCollection $documentCollection) {
		$category = $documentCollection->getCategory();
		// already persisted?
		$persistedCategory = $this->categoryRepository->findOneByPath($category->getPath());
		if ($persistedCategory instanceof Category) {
			$category = $persistedCategory;
		}
		$documents = $documentCollection->getDocuments();
		foreach ($documents AS $document) {
			if ($document->hasCategory($category) === FALSE) {
				$document->addCategory($category);
				$this->documentRepository->update($document);
			}
		}
		$this->addFlashMessage(count($documents) . ' Documents updated.');
		$this->redirect('index', 'Category', NULL, array('category' => $category));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentCollection $documentCollection
	 * @return void
	 */
	public function deleteAction(DocumentCollection $documentCollection) {
		$category = $documentCollection->getCategory();
		$documents = $documentCollection->getDocuments();
		foreach ($documents AS $document) {
			$document->removeCategory($category);
			$this->documentRepository->update($document);
		}
		$this->addFlashMessage(count($documents) . ' Documents updated.');
		$this->redirect('index', 'Category', NULL, array('category' => $category));
	}

}
