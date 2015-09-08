<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument AS Document;

/**
 * @Flow\Scope("prototype")
 */
class PdfExport {

	const SIZE_A0 = 0;
	const SIZE_A1 = 1;
	const SIZE_A2 = 2;
	const SIZE_A4 = 4;
	const SIZE_A5 = 5;
	const SIZE_A6 = 6;
	const ORIENTATION_PORTRAIT = 0;
	const ORIENTATION_LANDSCAPE = 1;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\ImageDocument>
	 */
	protected $documents;

	/**
	 * @var integer
	 */
	protected $columns = 3;

	/**
	 * @var integer
	 */
	protected $size = self::SIZE_A4;

	/**
	 * @var integer
	 */
	protected $dpi = 300;

	/**
	 * @var integer
	 */
	protected $orientation = self::ORIENTATION_PORTRAIT;


	/**
	 * @return void
	 */
	public function __construct() {
		$this->documents = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\ImageDocument>
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\AchimFritz\Documents\Domain\Model\ImageDocument $documents
	 * @return void
	 */
	public function setDocuments(\Doctrine\Common\Collections\Collection $documents) {
		$this->documents = $documents;
	}

	/**
	 * @param Document $document 
	 * @return void
	 */
	public function addDocument(Document $document) {
		$this->documents->add($document);
	}


	/**
	 * @return integer
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * @param integer $columns
	 * @return void
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
	}

	/**
	 * @return integer
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param integer $size
	 * @return void
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * @return integer
	 */
	public function getDpi() {
		return $this->dpi;
	}

	/**
	 * @param integer $dpi
	 * @return void
	 */
	public function setDpi($dpi) {
		$this->dpi = $dpi;
	}

	/**
	 * @return integer
	 */
	public function getOrientation() {
		return $this->orientation;
	}

	/**
	 * @param integer $orientation
	 * @return void
	 */
	public function setOrientation($orientation) {
		$this->orientation = $orientation;
	}

	/**
	 * @return integer
	 */
	protected function getPaperWidth() {
		// A0 1200 DPI: 39684
		$width = 39684;
		$width = $width * ($this->getDpi() / 1200);
		if ($this->getSize() !== self::SIZE_A0) {
			$width = $width / pow(2, ($this->getSize() / 2));
		}
		return (int)floor($width);
		// 841
		// A1 = 841 / sqrt(2) = 841 / 2^(1/2)
		// A2 = 841 / 2
		// A3 = 841 / 2^(3/2)
		// A4 = 841 / 4^(3/2)
		// A5 = 841 / 5^(3/2)
	}

	/**
	 * @return integer
	 */
	public function getRows() {
		return (int)floor($this->getColumns() * (4/3) * sqrt(2));
	}

	/**
	 * @return integer
	 */
	public function getHeight() {
		return (int)((3/4) * $this->getWidth());
	}

	/**
	 * @return integer
	 */
	public function getWidth() {
		$widthWithBorder = $this->getPaperWidth() / $this->getColumns();
		return (int)floor((10/11) * $widthWithBorder);
	}

	/**
	 * @return integer
	 */
	public function getBorder() {
		$widthWithBorder = $this->getPaperWidth() / $this->getColumns();
		return (int)floor((1/2) * (1/11) * $widthWithBorder);
	}

}
