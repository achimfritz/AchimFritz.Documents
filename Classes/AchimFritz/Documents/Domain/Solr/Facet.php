<?php
namespace AchimFritz\Documents\Domain\Solr;

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 */
class Facet {

	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var integer
	 */
	protected $countOfDocuments;

	/**
	 * __construct 
	 * 
	 * @param string $name 
	 * @param integer $countOfDocuments 
	 * @return void
	 */
	public function __construct($name, $countOfDocuments) {
		$this->name = $name;
		$this->countOfDocuments = $countOfDocuments;
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
	 * getCountOfDocuments 
	 * 
	 * @return integer
	 */
	public function getCountOfDocuments() {
		return $this->countOfDocuments;
	}
				
	
}

?>
