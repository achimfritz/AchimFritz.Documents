<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document as Document;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentCommandController extends AbstractFileSystemDocumentCommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;
		
	/**
	 * @var \AchimFritz\Documents\Domain\Model\Mp3DocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $id3TagFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService
	 * @Flow\Inject
	 */
	protected $id3TagWriterService;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService
	 * @Flow\Inject
	 */
	protected $cddbService;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\Mp3IndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3DocumentConfiguration;

	/**
	 * @return \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3DocumentConfiguration;
	}

	/**
	 * show --name=af/soundtrack/JudgementNight/01Justanothervictim.mp3
	 *
	 * @param string $name
	 * @return void
	 */
	public function showCommand($name) {
		parent::showCommand($name);
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof Document) {
			$this->outputLine('thumb: ' . $document->getWebThumbPath());
			$this->outputLine('relativeDirctoryName: ' . $document->getRelativeDirectoryName());
			try {
				$id3Tag = $this->id3TagFactory->create($document);
				$this->outputLine('id3Title: ' . $id3Tag->getTitle());
				$this->outputLine('id3Track: ' . $id3Tag->getTrack());
				$this->outputLine('id3Album: ' . $id3Tag->getAlbum());
				$this->outputLine('id3Artist: ' . $id3Tag->getArtist());
				$this->outputLine('id3Year: ' . $id3Tag->getYear());
				$this->outputLine('id3Genre: ' . $id3Tag->getGenre());
				$this->outputLine('id3GenreId: ' . $id3Tag->getGenreId());
				$this->outputLine('artistLetter: ' . $id3Tag->getArtistLetter());
			} catch (\AchimFritz\Documents\Linux\Exception $e) {
				$this->outputLine('ERROR cannot create id3Tag: ' . $e->getMessage() . ' - ' . $e->getCode());
			}

		}
	}


	/**
	 * tagCommand --name=af/soundtrack/JudgementNight/01Justanothervictim.mp3 --path=genre/Rock
	 * 
	 * @param string $name 
	 * @param string $path 
	 * @return void
	 */
	public function tagCommand($name, $path) {
		$documentCollection = $this->createDocumentCollection($name, $path);
		try {
			$this->id3TagWriterService->tagDocumentCollection($documentCollection);
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: write tag ' . $path);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\SolrClientException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * cddbCommand --path=tim/soundtrack/PulpFiction
	 *
	 * @param string $path
	 * @return void
	 */
	public function cddbCommand($path = 'tim/soundtrack/PulpFiction') {
		try {
			$this->cddbService->writeId3Tags($path);
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: write tag ' . $path);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\SolrClientException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}

	}
}
