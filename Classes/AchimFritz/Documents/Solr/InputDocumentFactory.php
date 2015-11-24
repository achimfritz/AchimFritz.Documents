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
class InputDocumentFactory implements InputDocumentFactoryInterface {

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
	 * @var \AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;
	
	/**
	 * @param \AchimFritz\Documents\Domain\Model\Document $document
	 * @return void
	 * @throws \AchimFritz\Documents\Linux\Exception
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
			$inputDocument->addField($solrField['name'], $solrField['value']);
			// paths
			$paths = $this->pathService->getPaths($category->getPath());
			foreach ($paths AS $path) {
				$inputDocument->addField('paths', $path);
			}
			// hPaths
			$paths = $this->pathService->getHierarchicalPaths($category->getPath());
			foreach ($paths AS $path) {
				$inputDocument->addField('hPaths', $path);
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
			$inputDocument->addField('mainDirectoryName', $document->getDirectoryName());
			$inputDocument->addField('fileName', $document->getFileName());
			$inputDocument->addField('webPath', $document->getWebPath());
			$inputDocument->addField('webPreviewPath', $document->getWebPreviewPath());
			$inputDocument->addField('webThumbPath', $document->getWebThumbPath());
			$inputDocument->addField('webBigPath', $document->getWebBigPath());
			$inputDocument->addField('extension', 'jpg');
			$inputDocument->addField('mDateTime', $document->getMDateTime()->format('Y-m-d\TH:i:s') . 'Z');
			$inputDocument->addField('fileHash', $document->getFileHash());
			$inputDocument->addField('year', $document->getYear());
			$inputDocument->addField('month', $document->getMonth());
			$inputDocument->addField('day', $document->getDay());
			$categories = $document->getCategories();
			foreach ($categories AS $category) {
				$paths = $this->pathService->splitPath($category->getPath());
				if ($paths[0] === 'locations' OR $paths[0] === 'categories') {
					$hPaths = $this->pathService->getHierarchicalPaths($category->getPath());
					foreach ($hPaths AS $hPath) {
						$inputDocument->addField('h' . ucfirst($paths[0]), $hPath);
					}
				}
				array_shift($paths);
				foreach ($paths as $path) {
					$inputDocument->addField('pathParts', $path);
				}
			}
		}
		return $inputDocument;
	}

	/**
	 * @param Document $document 
	 * @param \SolrInputDocument $inputDocument 
	 * @return \SolrInputDocument
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	protected function addMp3Fields(Document $document, \SolrInputDocument $inputDocument) {
		if ($document instanceof Mp3Document === TRUE) {
			$inputDocument->addField('mainDirectoryName', $document->getRelativeDirectoryName());
			$inputDocument->addField('extension', 'mp3');
			$inputDocument->addField('webPath', $document->getWebPath());
			$inputDocument->addField('mDateTime', $document->getMDateTime()->format('Y-m-d\TH:i:s') . 'Z');
			$inputDocument->addField('fileHash', $document->getFileHash());
			$inputDocument->addField('fsTitle', $document->getFsTitle());
			$inputDocument->addField('fsTrack', $document->getFsTrack());
			$inputDocument->addField('fsAlbum', $document->getFsAlbum());
			$inputDocument->addField('fsArtist', $document->getFsArtist());
			$inputDocument->addField('fsProvider', $document->getFsProvider());
			$inputDocument->addField('fsGenre', $document->getFsGenre());
			$inputDocument->addField('webThumbPath', $document->getWebThumbPath());
			$id3Tag = $this->id3TagFactory->create($document);
			$inputDocument->addField('length', $id3Tag->getLength());
			$inputDocument->addField('title', $id3Tag->getTitle());
			$inputDocument->addField('track', $id3Tag->getTrack());
			$inputDocument->addField('album', $id3Tag->getAlbum());
			$inputDocument->addField('artist', $id3Tag->getArtist());
			$inputDocument->addField('year', $id3Tag->getYear());
			$inputDocument->addField('genre', $id3Tag->getGenre());
			$inputDocument->addField('genreId', $id3Tag->getGenreId());
			$inputDocument->addField('bitrate', $id3Tag->getBitrate());
			$inputDocument->addField('artistLetter', $id3Tag->getArtistLetter());
			// ratings already added to field paths 
			$paths = array(
				'artist/' . $id3Tag->getArtist(),
				'album/' . $id3Tag->getAlbum(),
				'genre/' . $id3Tag->getGenre()
			);
			foreach ($paths AS $path) {
				$inputDocument->addField('paths', $path);
			}
			$categories = $document->getCategories();
			foreach ($categories AS $category) {
				$paths = $this->pathService->splitPath($category->getPath());
				if ($paths[0] === 'rating') { 
					$inputDocument->addField($paths[1] . 'Rating', (int)$paths[2]);
				}
			}

		}
		return $inputDocument;
	}
}
