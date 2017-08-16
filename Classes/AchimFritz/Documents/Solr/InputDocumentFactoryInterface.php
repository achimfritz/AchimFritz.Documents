<?php
namespace AchimFritz\Documents\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

/**
 */
interface InputDocumentFactoryInterface {

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return \SolrInputDocument
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	public function create(Document $document);

}
