<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\Document;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array(
		'category.path' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING
	);


	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */   
	protected $categoryRepository;

	/**
	 * @param string $path 
	 * @return DocumentList
	 */
	public function findOneByCategoryPath($path) {
		$query = $this->createQuery();
		$query->matching($query->equals('category.path', $path));
		return $query->execute()->getFirst();
	}

	/**
	 * @param DocumentList $documentList
	 * @return DocumentList
	 */
	public function getPersistedOrAdd(DocumentList $documentList) {
		$persistedCategory = $this->categoryRepository->getPersistedOrAdd($documentList->getCategory());
		$persisted = $this->findOneByCategory($persistedCategory);
		if ($persisted instanceof DocumentList === TRUE) {
			return $persisted;
		} else {
			$persisted = $this->getNewDocumentList();
			$persisted->setCategory($persistedCategory);
			$this->add($persisted);
			return $persisted;
		}
	}

	/**
	 * @return DocumentList
	 */
	protected function getNewDocumentList() {
		return new DocumentList();
	}


}
