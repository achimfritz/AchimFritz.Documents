<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class DocumentCollection {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 * @ORM\OneToMany(mappedBy="documentCollection")
	 */
	protected $documents;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Category
	 * @ORM\ManyToOne
	 */
	protected $category;


	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document $documents
	 * @return void
	 */
	public function setDocuments(\Doctrine\Common\Collections\Collection $documents) {
		$this->documents = $documents;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function setCategory(\AchimFritz\Documents\Domain\Model\Category $category) {
		$this->category = $category;
	}

}
