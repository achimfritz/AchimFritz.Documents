<?php
namespace AchimFritz\Documents\Domain\Solr;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\Common\Collections\ArrayCollection;
use AchimFritz\Documents\Domain\Model\Document;

/**
 * @Flow\Scope("singleton")
 */
class DocumentFactory {

	/**
	 * getNewObject 
	 * 
	 * @return Document
	 */
	protected function getNewObject() {
		return new Document();
	}

	/**
	 * create 
	 * 
	 * @param \SolrObject $result
	 * @return ArrayCollection<Document>
	 */
	public function create(\SolrObject $result) {
		$documents= new ArrayCollection();
		if (!empty($result->response->docs)) {
			foreach ($result->response->docs AS $doc) {
				$document = $this->getNewObject();
				$document->setName($doc->name);
				$documents->add($document);
			}
		}
		return $documents;
	}
	
}

?>
