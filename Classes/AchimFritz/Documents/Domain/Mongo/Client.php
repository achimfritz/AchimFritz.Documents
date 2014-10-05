<?php
namespace AchimFritz\Documents\Domain\Mongo;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;

/**
 * @Flow\Scope("singleton")
 */
class Client {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;
	
	/**
	 * @var array
	 */
	protected $settings;
				
	/**
	 * @var \MongoClient
	 */
	protected $solrClient;
	
	/**
	 * getSettings
	 * 
	 * @return array
	 */
	public function getSettings() {
		if (!isset($this->settings)) {
			$settings = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'AchimFritz.Documents');
			$this->settings = $settings['Mongo'];
		}
		return $this->settings;
	}
	
	/**
	 * getMongoClient
	 * 
	 * @return \MongoClient
	 */
	protected function getMongoClient() {
		// TODO getMongoCollection
		// connect
		#$m = new MongoClient();
		// select a database
		#$db = $m->comedy;
		// select a collection (analogous to a relational database's table)
		#$collection = $db->cartoons;
		if (!isset($this->mongoClient)) {
			$settings = $this->getSettings();
			$m = new \MongoClient($settings['server']);
			$db = $m->$settings['db'];
			$this->mongoClient = $db->$settings['collection'];
		}
		return $this->mongoClient;
	}
		
	/**
	 * deleteByIds
	 * 
	 * @param array $ids
	 * @return void
	 * @throws Exception
	 */
	public function deleteByIds(array $ids) {
		// TODO Exception
		foreach ($ids AS $id) {
			$this->deleteById($id);
		}
	}

	/**
	 * deleteById 
	 * 
	 * @param string $id
	 * @return void
	 * @throws Exception
	 */
	public function deleteById($id) {
		// TODO Exception
		$this->getMongoClient()->remove(array('_id' => $id));
	}

	/**
	 * rollback 
	 * 
	 * @return void
	 * @throws \SolrClientException
	 */
	public function rollback() {
		#$this->getSolrClient()->rollback();
	}
	
	/**
	 * addDocuments
	 * 
	 * @param array
	 * @return void
	 * @throws Exception
	 */
	public function addDocuments(array $documents) {
		$this->getMongoClient()->batchInsert($document);
	}
	
	/**
	 * updateDocument
	 * 
	 * @param array
	 * @return void
	 * @throws Exception
	 */
	public function updateDocument(array $document) {
		$this->getMongoClient()->update(array('_id' => $document['_id']), $document);
	}
	
	/**
	 * addDocument
	 * 
	 * @param array
	 * @return void
	 * @throws Exception
	 */
	public function addDocument(array $document) {
		$this->getMongoClient()->insert($document);
	}
	
	
	/**
	 * deleteByQueryString
	 * 
	 * @param string $queryString
	 * @return void
	 * @throws \SolrClientException
	 */
	public function deleteByQueryString($queryString) {
		#$this->getSolrClient()->deleteByQuery($queryString);
	}
	
	/**
	 * countByFacet
	 * 
	 * @param string $facet
	 * @param string $value
	 * @throws \SolrClientException
	 */
	public function countByFacet($facet, $value) {
		#$query = new \SolrQuery('*:*');
		#$query->addFilterQuery($facet . ':' . $value);
		#$queryResponse = $this->getSolrClient()->query($query);
		#$response = $queryResponse->getResponse();
		#return $response['response']->numFound;
	}
	
		
}

?>
