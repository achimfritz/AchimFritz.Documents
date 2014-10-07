<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * A Document
 *
 * @Flow\Entity
 * @ORM\InheritanceType("JOINED")
 */
class Document {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Category>
	 * @ORM\ManyToMany(mappedBy="documents", cascade={"persist", "merge", "detach"})
	 */
	protected $categories;

	/**
	 * @var string
	 * @Flow\Identity
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * __construct 
	 * 
	 * @return void
	 */
	public function __construct() {
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * addCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	public function addCategory(Category $category) {
		$this->categories->add($category);
		#if ($category->hasDocument($this) === FALSE) {
		#	$category->addDocument($this);
		#}
	}

	/**
	 * removeCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	public function removeCategory(Category $category) {
		#var_dump(count($this->categories));
		$this->categories->removeElement($category);
		#var_dump(count($this->categories));
		#if ($category->hasDocument($this) === TRUE) {
		#	$category->removeDocument($this);
		#}
	}

	/**
	 * getName 
	 * 
	 * @return string name
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

	/**
	 * getCategories
	 *
	 * @return Collection<Category>
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * setCategories
	 *
	 * @param Collection<Category>
	 * @return void
	 */
	public function setCategories(Collection $categories) {
		$this->categories = $categories;
	}


	/**
	 * hasCategory 
	 * 
	 * @param Category $category 
	 * @return boolean
	 */
	public function hasCategory(Category $category) {
		return $this->categories->contains($category);
	}

	/**
	 * getFields 
	 * 
	 * @return array
	 */
	public function getFields() {
		// TODO this should be fieldFactory ?
		$fields = array();
		$fields['name'] = $this->getName();
		$fields['paths'] = array();
		$fields['navigation'] = array();
		$categories = $this->getCategories();
		foreach ($categories AS $category) {
			$name = $category->getFieldName();
			$fields[$name][] = $category->getFieldValue();
			if ($category->isHierarchicalPath() === FALSE) {
				$fields['paths'][] = '0/'. $category->getFieldName();
				$fields['paths'][] = '1/'. $category->getFieldName() . '/' . $category->getFieldValue();
			} else {
				$paths = $category->getHierarchicalPaths();
				foreach ($paths AS $path) {
					$fields['navigation'][] = $path;
					$fields['paths'][] = $path;
				}
			}
		}
		return $fields;
	}

}
?>
