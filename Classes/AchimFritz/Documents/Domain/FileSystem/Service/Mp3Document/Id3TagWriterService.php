<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Mp3DocumentId3Tag;

/**
 * @Flow\Scope("singleton")
 */
class Id3TagWriterService {

	/**
	 * @var array<string>
	 */
	protected $validTagNames = array('artist', 'album', 'genre', 'year', 'title', 'track');

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
	 * @return integer
	 */
	public function tagDocumentCollection(DocumentCollection $documentCollection) {
		$documents = $documentCollection->getDocuments();
		$category = $documentCollection->getCategory();
		$paths = $this->pathService->splitPath($category->getPath());
		if (count($paths) !== 2) {
			throw new Exception('count of path must be 2 ' . $category->getPath(), 1433219573);
		}
		foreach ($documents AS $document) {	
			$this->tagDocument($document, $paths[0], $paths[1]);
		}
		return count($documents);
	}

	/**
	 * @param Mp3DocumentId3Tag $mp3DocumentId3Tag 
	 * @return void
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @throws \SolrClientException
	 */
	public function tagMp3DocumentId3Tag(Mp3DocumentId3Tag $mp3DocumentId3Tag) {
		$this->tagDocument($mp3DocumentId3Tag->getDocument(), $mp3DocumentId3Tag->getTagName(), $mp3DocumentId3Tag->getTagValue());
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
		$this->linuxCommand->writeId3Tag($document->getAbsolutePath(), $tagName, str_replace('"', '\"', $tagValue));
		// update solr via FLOW Persistence
		$this->documentRepository->update($document);
	}

	/**
	 * @param Mp3Document $document
	 * @return void
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	public function removeTags(Mp3Document $document) {
		$this->linuxCommand->removeId3Tags($document->getAbsolutePath());
	}

}
