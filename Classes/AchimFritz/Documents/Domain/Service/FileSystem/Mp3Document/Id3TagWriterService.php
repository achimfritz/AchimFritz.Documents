<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class Id3TagWriterService {

	/**
	 * @var array<string>
	 */
	protected $validTagNames = array('artist', 'album', 'genre', 'year');

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @param DocumentCollection $documentCollection
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @throws \SolrClientException
	 * @return void
	 */
	public function tagDocumentCollection(DocumentCollection $documentCollection) {
		$documents = $documentCollection->getDocuments();
		$category = $documentCollection->getCategory();
		$paths = $this->pathService->splitPaths($category->getPath());
		if (count($paths) !== 2) {
			throw new Exception('count of path must be 2 ' . $category->getPath(), 1433219573);
		}
		foreach ($documents AS $document) {	
			$this->tagDocument($document, $paths[0], $paths[1]);
		}
	}

	/**
	 * @param Mp3Document $document 
	 * @param string $tagName 
	 * @param string $tagValue 
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @throws \SolrClientException
	 * @return void
	 */
	public function tagDocument(Mp3Document $document, $tagName, $tagValue) {
		if (in_array($tagName, $this->validTagNames) === FALSE) {
			throw new Exception('no valid tagName: ' . $tagName, 1433219572);
		}
		$this->linuxCommand->writeId3Tag($document->getAbsolutePath(), $tagName, $tagValue);
		// update solr via FLOW Persistence
		$this->documentRepository->update($document);
	}

}
