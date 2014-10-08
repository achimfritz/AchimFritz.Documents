<?php
namespace AchimFritz\Documents\Domain\Mongo;

/*                                                                        *
 * This script belongs to the FLOW3 package "De.AchimFritz.Media".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * InputDocumentFactory
 *
 * @Flow\Scope("singleton")
 */
class InputDocumentFactory {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * create 
	 * 
	 * @param Document $document 
	 * @return string $json
	 */
	public function create(Document $document) {
		$fields = $document->getFields();
		$identifier = $this->persistenceManager->getIdentifierByObject($document);
		$fields['_id'] = $identifier;
		return $fields;
	}
}
?>
