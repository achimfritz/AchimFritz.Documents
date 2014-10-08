<?php
namespace AchimFritz\Documents\Domain\Solr;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Scope("singleton")
 */
class FacetContainerFactory {

	/**
	 * create 
	 * 
	 * @param \SolrObject $response 
	 * @param string $facetName 
	 * @return FacetContainer
	 */
	public function create(\SolrObject $response, $facetName) {
		$facetContainer = new FacetContainer();
		$facetContainer->setCountOfDocuments($response->response->numFound);
		$facets = new ArrayCollection();
		foreach ($response->facet_counts->facet_fields[$facetName] AS $key => $val) {
			$facet = new Facet($key, $val);
			$facets->add($facet);
		}
		$facetContainer->setFacets($facets);
		$facetContainer->setName('Solr');
		return $facetContainer;
	}
	
}

?>
