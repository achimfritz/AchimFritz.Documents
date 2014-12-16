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
class DocumentListItem {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\DocumentList
	 * @ORM\ManyToOne
	 */
	protected $documentList;


	/**
	 * @var \AchimFritz\Documents\Domain\Model\Document
	 * @ORM\ManyToOne
	 */
	protected $document;

	/**
	 * @var integer
	 */
	protected $sorting = 0;
}
