<?php
namespace AchimFritz\Documents\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Mp3Document;

/**
 * @Flow\Scope("singleton")
 */
class InputDocumentFactory {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;
	
	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 */
	public function create(Document $document) {
		$inputDocument = new \SolrInputDocument();
		$inputDocument = $this->addStandardFields($document, $inputDocument);
		$inputDocument = $this->addPathsFields($document, $inputDocument);
		$inputDocument = $this->addImageFields($document, $inputDocument);
		$inputDocument = $this->addMp3Fields($document, $inputDocument);
		return $inputDocument;
	}

	/**
	 * @param Document $document 
	 * @param \SolrInputDocument $inputDocument 
	 * @return \SolrInputDocument
	 */
	protected function addStandardFields(Document $document, \SolrInputDocument $inputDocument) {
		$identifier = $this->persistenceManager->getIdentifierByObject($document);
		$inputDocument->addField('identifier', $identifier);
		$inputDocument->addField('className', get_class($document));
		$inputDocument->addField('name', $document->getName());
		return $inputDocument;
	}


	/**
	 * @param Document $document 
	 * @param \SolrInputDocument $inputDocument 
	 * @return \SolrInputDocument
	 */
	protected function addPathsFields(Document $document, \SolrInputDocument $inputDocument) {
		$categories = $document->getCategories();
		foreach ($categories AS $category) {
			$solrField = $this->pathService->getSolrField($category->getPath());
			foreach ($solrField['values'] AS $value) {
				$inputDocument->addField($solrField['name'], $value);
			}
		}
		return $inputDocument;
	}

	/**
	 * @param Document $document 
	 * @param \SolrInputDocument $inputDocument 
	 * @return \SolrInputDocument
	 */
	protected function addImageFields(Document $document, \SolrInputDocument $inputDocument) {
		if ($document instanceof ImageDocument === TRUE) {
			$inputDocument->addField('fileName', $document->getFileName());
			$inputDocument->addField('mainDirectoryName', $document->getDirectoryName());
			$inputDocument->addField('webPath', $document->getWebPath());
			$inputDocument->addField('webPreviewPath', $document->getWebPreviewPath());
			$inputDocument->addField('webThumbPath', $document->getWebThumbPath());
			$inputDocument->addField('webBigPath', $document->getWebBigPath());
			$inputDocument->addField('extension', 'jpg');
			$inputDocument->addField('year', $document->getYear());
			$inputDocument->addField('month', $document->getMonth());
			$inputDocument->addField('day', $document->getDay());
			/*
			$imageSize = getimagesize($document->getAbsolutePath());
			if ($imageSize[0] < $imageSize[1]) {
				$inputDocument->addField('isUpright', TRUE);
			} else {
				$inputDocument->addField('isUpright', FALSE);
			}
			*/
		}
		return $inputDocument;
	}

	/**
	 * @param Document $document 
	 * @param \SolrInputDocument $inputDocument 
	 * @return \SolrInputDocument
	 */
	protected function addMp3Fields(Document $document, \SolrInputDocument $inputDocument) {
		if ($document instanceof Mp3Document === TRUE) {
		}
		return $inputDocument;
	}
}
