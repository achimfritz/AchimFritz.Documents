<?php
namespace AchimFritz\Documents\Domain\Solr;

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
	 * @var \SolrClient
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
			$this->settings = $settings['Solr'];
		}
		return $this->settings;
	}
	
	/**
	 * getSolrClient
	 * 
	 * @return \SolrClient
	 */
	protected function getSolrClient() {
		if (!isset($this->solrClient)) {
			$settings = $this->getSettings();
			$this->solrClient = new \SolrClient($settings);
			if (isset($settings['servlet'])) {
				$this->solrClient->setServlet(\SolrClient::SEARCH_SERVLET_TYPE, $settings['servlet']);
			}
		}
		return $this->solrClient;
	}
		
	/**
	 * commit
	 * 
	 * @return void
	 * @throws \SolrClientException
	 */
	public function commit() {
		$this->getSolrClient()->commit();
	}

	/**
	 * deleteByIds
	 * 
	 * @param array $ids
	 * @return void
	 * @throws \SolrClientException
	 */
	public function deleteByIds(array $ids) {
		$this->getSolrClient()->deleteByIds($ids);
	}

	/**
	 * deleteById 
	 * 
	 * @param string $id
	 * @return void
	 * @throws \SolrClientException
	 */
	public function deleteById($id) {
		$this->getSolrClient()->deleteById($id);
	}

	/**
	 * rollback 
	 * 
	 * @return void
	 * @throws \SolrClientException
	 */
	public function rollback() {
		$this->getSolrClient()->rollback();
	}
	
	/**
	 * addDocuments
	 * 
	 * @param array<\SolrInputDocument>
	 * @return void
	 * @throws \SolrClientException
	 */
	public function addDocuments(array $documents) {
		$this->getSolrClient()->addDocuments($documents);
	}
	
	/**
	 * addDocument
	 * 
	 * @param \SolrInputDocument
	 * @return void
	 * @throws \SolrClientException
	 */
	public function addDocument(\SolrInputDocument $document) {
		$this->getSolrClient()->addDocument($document);
	}
	
	
	/**
	 * deleteByQueryString
	 * 
	 * @param string $queryString
	 * @return void
	 * @throws \SolrClientException
	 */
	public function deleteByQueryString($queryString) {
		$this->getSolrClient()->deleteByQuery($queryString);
	}

	/**
	 * deleteAll 
	 * 
	 * @return void
	 * @throws \SolrClientException
	 */
	public function deleteAll() {
		return $this->deleteByQueryString('*:*');
	}

	/**
	 * findByQuery 
	 * 
	 * @param \SolrQuery $query 
	 * @return \SolrObject
	 */
	public function findByQuery(\SolrQuery $query) {
		$queryResponse = $this->getSolrClient()->query($query);
		$response = $queryResponse->getResponse();
		return $response;
	}
	
}

?>
