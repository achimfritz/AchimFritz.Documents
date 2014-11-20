<?php
namespace AchimFritz\Documents\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;

/**
 * @Flow\Scope("singleton")
 */
class ClientWrapper {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;
				
	/**
	 * @var \SolrClient
	 */
	protected $solrClient;
	
	/**
	 * @return void
	 */
	public function initializeObject() {
		$settings = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'AchimFritz.Documents');
		$this->solrClient = new \SolrClient($settings['Solr']);
		if (isset($settings['Solr']['servlet'])) {
			$this->solrClient->setServlet(\SolrClient::SEARCH_SERVLET_TYPE, $settings['Solr']['servlet']);
		}
	}

	/**
	 * @param string $method 
	 * @param array $arguments 
	 * @return mixed
	 */
	public function __call($method, $arguments) {
		return call_user_func_array(array($this->solrClient, $method), $arguments);
	}

	/**
	 * @param array<\SolrInputDocument> $solrInputDocuments 
	 * @return \SolrUpdateResponse
	 */
	public function addDocuments(array $solrInputDocuments) {
		return $this->solrClient->addDocuments($solrInputDocuments);
	}
}
