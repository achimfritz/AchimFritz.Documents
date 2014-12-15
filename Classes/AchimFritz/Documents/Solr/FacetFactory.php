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
class FacetFactory {

	const FACET_LIMIT = 5000;

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
	public function find($name) {
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
}
