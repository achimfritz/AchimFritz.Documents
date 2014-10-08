<?php
namespace AchimFritz\Documents\Domain\Solr;

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
	 * @return \SolrInputDocument
	 */
	public function create(Document $document) {
		$inputDocument = new \SolrInputDocument();
		$identifier = $this->persistenceManager->getIdentifierByObject($document);
		$inputDocument->addField('identifier', $identifier);
		$fields = $document->getFields();
		foreach ($fields AS $key => $val) {
			if (is_array($val)) {
				foreach ($val AS $value) {
					$inputDocument->addField($key, $value);
				}
			} else {
				$inputDocument->addField($key, $val);
			}
		}
		return $inputDocument;
	}
}
?>
