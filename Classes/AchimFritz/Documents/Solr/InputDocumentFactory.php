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
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $imageFileSystemFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;
	
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
			// paths
			$paths = $this->pathService->getHierarchicalPaths($category->getPath());
			foreach ($paths AS $path) {
				$inputDocument->addField('paths', $path);
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
			$fileSystem = $this->imageFileSystemFactory->create($document);
			$inputDocument->addField('mainDirectoryName', $document->getDirectoryName());
			$inputDocument->addField('fileName', $fileSystem->getFileName());
			$inputDocument->addField('webPath', $fileSystem->getWebPath());
			$inputDocument->addField('webPreviewPath', $fileSystem->getWebPreviewPath());
			$inputDocument->addField('webThumbPath', $fileSystem->getWebThumbPath());
			$inputDocument->addField('webBigPath', $fileSystem->getWebBigPath());
			$inputDocument->addField('extension', 'jpg');
			$inputDocument->addField('mDateTime', $document->getMDateTime()->format('Y-m-d\TH:i:s') . 'Z');
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
