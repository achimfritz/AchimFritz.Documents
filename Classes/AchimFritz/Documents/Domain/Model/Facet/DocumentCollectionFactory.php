<?php
namespace AchimFritz\Documents\Domain\Model\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class DocumentCollectionFactory {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @param string $path
	 * @param \Doctrine\Common\Collections\Collection $categories
	 * @return \AchimFritz\Documents\Domain\Model\DocumentCollection
	 */
	public function createInCategories($path, \Doctrine\Common\Collections\Collection $categories) {
		$documents = $this->documentRepository->findInAllCategories($categories);
		$category = new Category();
		$category->setPath($path);
		$documentCollection = new DocumentCollection();
		$documentCollection->setCategory($category);
		foreach ($documents as $document) {
			$documentCollection->addDocument($document);
		}
		return $documentCollection;
	}

}
