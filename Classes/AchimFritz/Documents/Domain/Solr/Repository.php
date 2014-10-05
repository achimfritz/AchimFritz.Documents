<?php
namespace AchimFritz\Documents\Domain\Solr;

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class Repository {

	const MAX_DOCUMENTS = 9999999;

	/**
	 * @var \AchimFritz\Documents\Domain\Solr\InputDocumentFactory
	 * @Flow\Inject
	 */
	protected $inputDocumentFactory; 

	/**
	 * @var \AchimFritz\Documents\Domain\Solr\Client
	 * @Flow\Inject
	 */
	protected $solrClient;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * update
	 *
	 * @param Document $document
	 * @return void
	 * @throws \SolrClientException
	 */
	public function update(Document $document) {
		$inputDocument = $this->inputDocumentFactory->create($document);
		$this->solrClient->addDocument($inputDocument);
	}

	/**
	 * remove
	 * 
	 * @param Document $document
	 * @return void
	 * @throws \SolrClientException
	 */
	public function remove(Document $document) {
		$id = $this->persistenceManager->getIdentifierByObject($document);
		$this->solrClient->deleteById($id);
	}

	/**
	 * removeByQueryString 
	 * 
	 * @param string $string 
	 * @return void
	 * @throws \SolrClientException
	 */
	public function removeByQueryString($string) {
		$this->solrClient->deleteByQueryString($string);
	}

	/**
	 * removeAll 
	 * 
	 * @return void
	 */
	public function removeAll() {
		$this->solrClient->deleteAll();
	}

	/**
	 * updateCategory
	 *
	 * @param Category $category
	 * @return void
	 * @throws \SolrClientException
	 */
	public function updateCategory(Category $category) {
		$inputDocuments = array();
		foreach ($category->getDocuments() AS $document) {
			$inputDocument = $this->inputDocumentFactory->create($document);
			$inputDocuments[] = $inputDocument;
		}
		if (count($inputDocuments)) {
			$this->solrClient->addDocuments($inputDocuments);
		}
	}

	/**
	 * countAll 
	 * 
	 * @return integer
	 * @throws \SolrClientException
	 */
	public function countAll() {
		$query = $this->getQuery();
		$query->setQuery('*:*')->setRows(0)->setStart(0);
		$result = $this->solrClient->findByQuery($query);
		return $result->response->numFound;
	}

	/**
	 * findByFacet 
	 * 
	 * @param string $name 
	 * @param string $value
	 * @throws \SolrClientException
	 * @return \SolrQueryResponse
	 */
	public function findByFacet($name, $value) {
		$query = $this->getQuery();
		$query->setQuery('*:*')->setRows(self::MAX_DOCUMENTS)->setStart(0)->addFilterQuery($name . ' : ' . $value);
		$result = $this->solrClient->findByQuery($query);
		return $result;
	}

	/**
	 * findFacets 
	 * 
	 * @param string $name 
	 * @throws \SolrClientException
	 * @return \SolrQueryResponse
	 */
	public function findFacets($name) {
		$query = $this->getQuery();
		$query->setQuery('*:*')->setRows(0)->setStart(0)->setFacet(true)->addFacetField($name)->setFacetLimit(self::MAX_DOCUMENTS)->setFacetMincount(1);
		$result = $this->solrClient->findByQuery($query);
		return $result;
	}

	/**
	 * getQuery 
	 * 
	 * @return \SolrQuery
	 */
	protected function getQuery() {
		$query = new \SolrQuery();
		return $query;
	}

		
}

?>
