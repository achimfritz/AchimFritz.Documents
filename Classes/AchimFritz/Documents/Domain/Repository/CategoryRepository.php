<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;

/**
 * @Flow\Scope("singleton")
 */
class CategoryRepository extends Repository {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */   
	protected $documentRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @var \AchimFritz\Documents\Solr\InputDocumentFactoryInterface
	 * @Flow\Inject
	 */
	protected $solrInputDocumentFactory;

	/**
	 * @Flow\Inject
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @param Category $category 
	 * @return Category
	 */
	public function getPersistedOrAdd(Category $category) {
		// already persisted?
		$persistedCategory = $this->findOneByPath($category->getPath());
		if ($persistedCategory instanceof Category) {
			$category = $persistedCategory;
		} else {
			$this->add($category);
		}
		return $category;
	}

	/**
	 * @param object $object The object to remove
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @api
	 */
	public function remove($object) {
		$category = $object;
		$documents = $this->documentRepository->findByCategory($category);
		if (count($documents) > 0) {
			foreach($documents AS $document) {
				if ($document->hasCategory($category) === TRUE) {
					throw new Exception('category has documents ' . $category->getPath(), 1417447460);
				}
			}
		}
		$documentList = $this->documentListRepository->findOneByCategory($category);
		if ($documentList instanceof DocumentList) {
			throw new Exception('documentList exists ' . $category->getPath(), 1417447461);
		}
		return $this->parentRemove($category);
	}

	/**
	 * Schedules a modified object for persistence.
	 *
	 * @param object $object The modified object
	 * @throws \SolrClientException
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @api
	 */
	public function update($object) {
		$uow = $this->entityManager->getUnitOfWork();
		$originalEntityData = $uow->getOriginalEntityData($object);
		$this->parentUpdate($object);
		$this->updateDocuments($object);

		if ($originalEntityData['path'] !== $object->getPath()) {
			$childs = $this->findByPathHead($originalEntityData['path']);
			foreach ($childs AS $category) {
				$replacedPath = $this->pathService->replacePath($category->getPath(), $originalEntityData['path'], $object->getPath());
				$category->setPath($replacedPath);
				$this->parentUpdate($category);
				$this->updateDocuments($category);
			}
		}
	}

	/**
	 * @param Category $category 
	 * @return void
	 */
	protected function parentUpdate(Category $category) {
		parent::update($category);
	}

	/**
	 * @param Category $category 
	 * @return void
	 * @throws \SolrClientException
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	protected function updateDocuments(Category $category) {
		$documents = $this->documentRepository->findByCategory($category);
		$solrInputDocuments = array();
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($document);
			$solrInputDocuments[] = $solrInputDocument;
		}
		if (count($solrInputDocuments) > 0) {
			$this->solrClientWrapper->addDocuments($solrInputDocuments);
		}
	}

	/**
	 * @param Category $category 
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findChilds(Category $category) {
		$path = $category->getPath();
		return $this->findByPathHead($path);
	}

	/**
	 * @param string $pathHead
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findByPathHead($pathHead) {
		$query = $this->createQuery();
		return $query->matching(
			$query->like('path', $pathHead . '/%', FALSE)
		)->execute();
	}


	/**
	 * @param mixed $object 
	 * @return void
	 */
	protected function parentRemove($object) {
		return parent::remove($object);
	}


}
