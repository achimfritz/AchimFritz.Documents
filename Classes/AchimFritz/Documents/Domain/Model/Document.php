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
class Document {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Category>
	 * @ORM\ManyToMany(cascade={"persist"})
	 */
	protected $categories;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\DocumentCollection>
	 * @ORM\ManyToOne
	 */
	protected $documentCollection;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Category>
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Category> $categories
	 * @return void
	 */
	public function setCategories(\Doctrine\Common\Collections\Collection $categories) {
		$this->categories = $categories;
	}

	/**
	 * @param Category $category 
	 * @return void
	 */
	public function addCategory(Category $category) {
		$this->categories->add($category);
	}

	/**
	 * @param Category $category 
	 * @return void
	 */
	public function removeCategory(Category $category) {
		$this->categories->removeElement($category);
	}

	/**
	 * @param Category $category 
	 * @return boolean
	 */
	public function hasCategory(Category $category) {
		return $this->categories->contains($category);
	}


}
