<?php
namespace AchimFritz\Documents\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Helper {

	const FACET_LIMIT = 5000;
	const DOCUMENT_LIMIT = 5000;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @param string $name 
	 * @throws \SolrClientException
	 * @return array
	 */
	public function findFacets($name) {
		$facets = [];
		$query = new \SolrQuery();
		$query->setQuery('*:*')->setRows(0)->setStart(0)->setFacet(true)->addFacetField($name)->setFacetLimit(self::FACET_LIMIT)->setFacetMincount(1);

		$queryResponse = $this->solrClientWrapper->query($query);
		$response = $queryResponse->getResponse();
		foreach ($response->facet_counts->facet_fields[$name] AS $key => $val) {
			$facets[$key] = $val;
		}
		return $facets;
	}

	/**
	 * @param string $fq 
	 * @return array
	 * @throws \SolrClientException
	 */
	public function findDocumentsByFq($fq) {
		$documents = array();
		$query = new \SolrQuery();
		$query->setQuery('*:*')->setRows(self::DOCUMENT_LIMIT)->setStart(0)->addFilterQuery($fq);
		$queryResponse = $this->solrClientWrapper->query($query);
		if ($queryResponse->getResponse()->response->docs) {
			foreach ($queryResponse->getResponse()->response->docs AS $doc) {
				$documents[] = $doc->fileName;
			}
		}
		return $documents;
	}
}
