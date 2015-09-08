<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Integrity {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $countFileSystem;

	/**
	 * @var integer
	 */
	protected $countSolr;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 */
	protected $persistedDocuments;

	/**
	 * @var array
	 */
	protected $solrDocuments;

	/**
	 * @var array
	 */
	protected $filesystemDocuments;

	/**
	 * @param string $name 
	 * @param integer $countFileSystem 
	 * @param integer $countSolr 
	 * @return void
	 */
	public function __construct($name, $countFileSystem, $countSolr) {
		$this->name = $name;
		$this->countFileSystem = $countFileSystem;
		$this->countSolr = $countSolr;
	}

	/**
	 * @return boolean
	 */
	public function getCountDiffers() {
		if ($this->countFileSystem === $this->countSolr) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return integer
	 */
	public function getCountFileSystem() {
		return $this->countFileSystem;
	}

	/**
	 * @return integer
	 */
	public function getCountSolr() {
		return $this->countSolr;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 */
	public function getPersistedDocuments() {
		return $this->persistedDocuments;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document> $persistedDocuments
	 * @return void
	 */
	public function setPersistedDocuments($persistedDocuments) {
		$this->persistedDocuments = $persistedDocuments;
	}

	/**
	 * @return array
	 */
	public function getSolrDocuments() {
		return $this->solrDocuments;
	}

	/**
	 * @param array $solrDocuments
	 * @return void
	 */
	public function setSolrDocuments(array $solrDocuments) {
		$this->solrDocuments = $solrDocuments;
	}

	/**
	 * @return array
	 */
	public function getFilesystemDocuments() {
		return $this->filesystemDocuments;
	}

	/**
	 * @param array $filesystemDocuments
	 * @return void
	 */
	public function setFilesystemDocuments(array $filesystemDocuments) {
		$this->filesystemDocuments = $filesystemDocuments;
	}

}
