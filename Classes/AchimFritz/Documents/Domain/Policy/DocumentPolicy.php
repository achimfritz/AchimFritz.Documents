<?php
namespace AchimFritz\Documents\Domain\Policy;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\DocumentList;

/**
 * @Flow\Scope("singleton")
 */
class DocumentPolicy {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentListRepository
	 */
	protected $documentListRepository;

	/**
	 * @param Category $category 
	 * @param Document $document 
	 * @return boolean
	 */
	public function categoryMayBeRemoved(Category $category, Document $document) {
		$documentList = $this->documentListRepository->findOneByCategory($category);
		if ($documentList instanceof DocumentList === FALSE || $documentList->hasDocument($document) === FALSE) {
			if ($document->hasCategory($category) === TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}

}
