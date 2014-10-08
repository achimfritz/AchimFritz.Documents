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
 * A Category
 *
 * @Flow\Entity
 * @ORM\InheritanceType("JOINED")
 */
class Category {

	const PATH_DELIMITER = '/';

	/**
	 * @var string
	 * @Flow\Identity
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $path;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\Document>
	 * @ORM\ManyToMany(inversedBy="categories", cascade={"persist", "merge", "detach"})
	 */
	protected $documents;

	/**
	 * __construct 
	 * 
	 * @return void
	 */
	public function __construct() {
		$this->documents = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * addDocument 
	 * 
	 * @param Document $document 
	 * @return void
	 */
	public function addDocument(Document $document) {
		$this->documents->add($document);
		if ($document->hasCategory($this) === FALSE) {
			$document->addCategory($this);
		}
	}

	/**
	 * removeDocument 
	 * 
	 * @param Document $document 
	 * @return void
	 */
	public function removeDocument(Document $document) {
		$this->documents->removeElement($document);
		if ($document->hasCategory($this) === TRUE) {
			$document->removeCategory($this);
		}
	}
	/**
	 * getPath
	 *
	 * @return string 
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * setPath
	 *
	 * @param string
	 * @return void
	 */
	public function setPath($path) {
		$this->path = $path;
		$paths = $this->splitPath();
		if (count($paths) < 2) {
			throw new Exception('invalide path '. $path, 1382281950);
		}
	}

	/**
	 * splitPath 
	 * 
	 * @return array
	 */
	protected function splitPath() {
		return explode(self::PATH_DELIMITER, $this->getPath());
	}

	/**
	 * getFieldName 
	 * 
	 * @return string
	 */
	public function getFieldName() {
		$paths = $this->splitPath();
		return $paths[0];
	}

	/**
	 * getFieldValue 
	 * 
	 * @return string
	 */
	public function getFieldValue() {
		$paths = $this->splitPath();
		array_shift($paths);
		return implode(self::PATH_DELIMITER, $paths);
	}

	/**
	 * getHierarchicalPaths
	 * 0/foo
	 * 1/foo/bar
	 * 2/foo/bar/baz
	 * if isHierarchicalPath() -> count(path) > 2
	 * 
	 * @return array
	 */
	public function getHierarchicalPaths() {
		$hierarchicalPaths = array();
		$paths = $this->splitPath();
		$hierarchicalPath = '';
		if ($this->isHierarchicalPath() === TRUE) {
			$k = 0;
			foreach ($paths AS $path) {
				if ($k != 0) {
					$hierarchicalPath .= '/';
				}
				$hierarchicalPath .= $path;
				$hierarchicalPaths[] = $k . '/' . $hierarchicalPath;
				$k++;
			}
		}
		return $hierarchicalPaths;
	}

	/**
	 * isHierarchicalPath 
	 * 
	 * @return boolean
	 */
	public function isHierarchicalPath() {
		$paths = $this->splitPath();
		if (count($paths) > 2) {
			return TRUE;
		}
		return FALSE;
	}



	/**
	 * getDocuments
	 *
	 * @return Collection<Document> 
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * setDocuments
	 *
	 * @param Collection<Document>
	 * @return void
	 */
	public function setDocuments(Collection $documents) {
		$this->documents = $documents;
	}


	/**
	 * hasDocument 
	 * 
	 * @param Document $document 
	 * @return boolean
	 */
	public function hasDocument(Document $document) {
		return $this->documents->contains($document);
	}

	/**
	 * removeAllDocumentsFromCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	public function removeAllDocumentsFromCategory(Category $category) {
		foreach ($category->getDocuments() AS $document) {
			if ($this->hasDocument($document) === TRUE) {
				$this->removeDocument($document);
			}
		}
	}

	/**
	 * addAllDocumentsFromCategory 
	 * 
	 * @param Category $category 
	 * @return void
	 */
	public function addAllDocumentsFromCategory(Category $category) {
		foreach ($category->getDocuments() AS $document) {
			if ($this->hasDocument($document) === FALSE) {
				$this->addDocument($document);
			}
		}
	}

	/**
	 * replacePath 
	 * 
	 * @param string $search 
	 * @param string $replace 
	 * @return void
	 */
	public function replacePath($search, $replace) {
		$path = $this->getPath();
		$search = preg_quote($search, self::PATH_DELIMITER);
		$path = preg_replace('/^' . $search . '/', $replace, $path);
		$this->setPath($path);
	}

}
?>
