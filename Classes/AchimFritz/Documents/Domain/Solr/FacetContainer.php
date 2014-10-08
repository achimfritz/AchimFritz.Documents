<?php
namespace AchimFritz\Documents\Domain\Solr;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Flow\Entity
 */
class FacetContainer {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Solr\Facet>
	 */
	protected $facets;

	/**
	 * @var integer
	 */
	protected $countOfDocuments;

	/**
	 * @var string
	 */
	protected $name;

	public function __construct() {
		$this->facets = new ArrayCollection();
	}

	/**
	 * getAssoc 
	 * 
	 * @return array
	 */
	public function getAssoc() {
		$assoc = array();
		$facets = $this->getFacets();
		foreach ($facets as $facet) {
			$assoc[$facet->getName()] = $facet->getCountOfDocuments();
		}
		return $assoc;
	}

	/**
	 * setFacets 
	 * 
	 * @param ArrayCollection<Facet> $facets 
	 * @return void
	 */
	public function setFacets(ArrayCollection $facets) {
		$this->facets = $facets;
	}

	/**
	 * getCountOfFacets 
	 * 
	 * @return integer
	 */
	public function getCountOfFacets() {
		return count($this->facets);
	}

	/**
	 * setCountOfDocuments 
	 * 
	 * @param integer $countOfDocuments 
	 * @return void
	 */
	public function setCountOfDocuments($countOfDocuments) {
		$this->countOfDocuments = $countOfDocuments;
	}

	/**
	 * getCountOfDocuments 
	 * 
	 * @return integer
	 */
	public function getCountOfDocuments() {
		return $this->countOfDocuments;
	}

	/**
	 * getFacets
	 * 
	 * @return ArrayCollection
	 */
	public function getFacets() {
		return $this->facets;
	}
	
	/**
	 * getName 
	 * 
	 * @return name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * setName 
	 * 
	 * @param string $name 
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

}

?>
