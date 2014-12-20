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
	 * @var \SolrClient
	 */
	protected $solrClient;
	
	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}
	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->solrClient = new \SolrClient($this->settings['solr']);
		if (isset($this->settings['solr']['servlet'])) {
			$this->solrClient->setServlet(\SolrClient::SEARCH_SERVLET_TYPE, $this->settings['solr']['servlet']);
		}
	}

	/**
	 * @param string $method 
	 * @param array $arguments 
	 * @return mixed
	 */
	public function __call($method, $arguments) {
		if (count($arguments) === 1) {
			return $this->solrClient->$method($arguments[0]);
		} else {
			// TODO throw exception ...
			return call_user_func_array(array($this->solrClient, $method), $arguments);
		}
	}
}
