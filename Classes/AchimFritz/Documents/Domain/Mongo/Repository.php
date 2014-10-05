<?php
namespace AchimFritz\Documents\Domain\Mongo;

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class Repository {

	/**
	 * @var \AchimFritz\Documents\Domain\Mongo\InputDocumentFactory
	 * @Flow\Inject
	 */
	protected $inputDocumentFactory; 

	/**
	 * @var \AchimFritz\Documents\Domain\Mongo\Client
	 * @Flow\Inject
	 */
	protected $mongoClient;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * add
	 *
	 * @param Document $document
	 * @return void
	 * @throws Exception
	 */
	public function add(Document $document) {
		#$inputDocument = $this->inputDocumentFactory->create($document);
		#$this->mongoClient->addDocument($inputDocument);
	}

	/**
	 * update
	 *
	 * @param Document $document
	 * @return void
	 * @throws Exception
	 */
	public function update(Document $document) {
		#$inputDocument = $this->inputDocumentFactory->create($document);
		#$this->mongoClient->updateDocument($inputDocument);
	}

	/**
	 * remove
	 * 
	 * @param Document $document
	 * @return void
	 * @throws Exception
	 */
	public function remove(Document $document) {
		#$id = $this->persistenceManager->getIdentifierByObject($document);
		#$this->mongoClient->deleteById($id);
	}

	/**
	 * updateCategory
	 *
	 * @param Category $category
	 * @return void
	 * @throws Exception
	 */
	public function updateCategory(Category $category) {
		return;
		$inputDocuments = array();
		foreach ($category->getDocuments() AS $document) {
			$inputDocument = $this->inputDocumentFactory->create($document);
			$inputDocuments[] = $inputDocument;
		}
		if (count($inputDocuments)) {
			$this->mongoClient->addDocuments($inputDocuments);
		}
	}

		
}

?>
