<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Entity
 */
class ImageDocument extends FileSystemDocument {

	/**
	 * @var string
	 */
	protected $fileHash;

	/**
	 * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $imageDocumentConfiguration;

	/**
	 * @return \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->imageDocumentConfiguration;
	}

	/**
	 * @return integer
	 */
	public function getYear() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[0];
	}

	/**
	 * @return integer
	 */
	public function getMonth() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[1];
	}

	/**
	 * @return integer
	 */
	public function getDay() {
		$arr = explode('_', $this->getDirectoryName());
		return (int)$arr[2];
	}

	/**
	 * @return string
	 */
	public function getWebBigPath() {
		return $this->getConfiguration()->getWebBigPath() . PathService::PATH_DELIMITER . $this->getName();
	}


	/**
	 * @return string
	 */
	public function getWebPreviewPath() {
		return $this->getConfiguration()->getWebPreviewPath() . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getWebThumbPath() {
		return $this->getConfiguration()->getWebThumbPath() . PathService::PATH_DELIMITER . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getAbsoluteWebThumbPath() {
		// TODO rename to getAbsoluteWebPath
		// TODO not here ...
		return FLOW_PATH_WEB . $this->getWebPath();
	}

	/**
	 * getSearch 
	 * 
	 * @access public
	 * @return void
	 */
	public function getSearch() {
		$fields = array();
		$categories = $this->getCategories();
		foreach ($categories AS $category) {
			$path = $category->getPath();
			$paths = explode('/', $path);
			array_shift($paths);
			foreach ($paths AS $pathPart) {
				$parts = explode('_', $pathPart);
				foreach ($parts AS $part) {
					$fields[] = $part;
				}
			}
		}
		$pathPart = $this->getDirectoryName();
		$parts = explode('_', $pathPart);
		array_shift($parts);
		array_shift($parts);
		array_shift($parts);
		foreach ($parts AS $part) {
			$fields[] = $part;
		}
		return array_unique($fields);
	}


}
