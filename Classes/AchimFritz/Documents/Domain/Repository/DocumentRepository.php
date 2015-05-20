<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Persistence\Repository;
use TYPO3\Flow\Persistence\QueryInterface;

/**
 * @Flow\Scope("singleton")
 */
class DocumentRepository extends Repository {

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */   
	protected $categoryRepository;


	/**
	 * @var \AchimFritz\Documents\Solr\InputDocumentFactoryInterface
	 * @Flow\Inject
	 */
	protected $solrInputDocumentFactory;

	/**
	 * @param string $head
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByHead($head) {
		$query = $this->createQuery();
		$query->setOrderings(array('name' => QueryInterface::ORDER_ASCENDING));
		return $query->matching(
			$query->like('name', $head . '/%', FALSE)
		)->execute();
	}

	/**
	 * @param Category $category 
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByCategory(Category $category) {
		$query = $this->createQuery();
		return $query->matching(
			$query->contains('categories', $category)
		)->execute();
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $categories
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByCategoryPaths(array $paths) {
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($paths as $path) {
			$category = $this->categoryRepository->findOneByPath($path);
			if ($category instanceof Category === TRUE) {
				$categories->add($category);
			}
		}
		return $this->findInAllCategories($categories);
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $categories
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findInAllCategories(\Doctrine\Common\Collections\Collection $categories) {
		$query = $this->createQuery();
		$constraints = [];
		foreach ($categories AS $category) {
			$constraints[] = $query->contains('categories', $category);
		}
		return $query->matching($query->logicalAnd($constraints))->execute();
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $categories
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findInOneCategories(\Doctrine\Common\Collections\Collection $categories) {
		$query = $this->createQuery();
		$constraints = [];
		foreach ($categories AS $category) {
			$constraints[] = $query->contains('categories', $category);
		}
		return $query->matching($query->logicalOr($constraints))->execute();
	}

	/**
	 * Adds an object to this repository.
	 *
	 * @param object $object The object to add
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @api
	 */
	public function add($object) {
		parent::add($object);
		$solrInputDocument = $this->solrInputDocumentFactory->create($object);
		$this->solrClientWrapper->addDocument($solrInputDocument);
	}

	/**
	 * Removes an object from this repository.
	 *
	 * @param object $object The object to remove
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @api
	 */
	public function remove($object) {
		parent::remove($object);
		$identifier = $this->persistenceManager->getIdentifierByObject($object);
		$this->solrClientWrapper->deleteById($identifier);
	}

	/**
	 * Schedules a modified object for persistence.
	 *
	 * @param object $object The modified object
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @api
	 */
	public function update($object) {
		parent::update($object);
		$solrInputDocument = $this->solrInputDocumentFactory->create($object);
		$this->solrClientWrapper->addDocument($solrInputDocument);
	}



}
