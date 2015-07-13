<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Mp3Document as Document;

/**
 * @Flow\Scope("singleton")
 */
class CddbService {

	const FILENAME = 'cddb.txt';
	const CDDB_TITLE = 'TTITLE';
	const LINE_DELIMITER = '=';
	const ARTIST_TITLE_SEPERATED_BY_MINUS_STRATEGY = 1;
	const TITLE_ONY_STRATEGY = 2;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService
	 * @Flow\Inject
	 */
	protected $id3TagWriterService;

	/*
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @throws \SolrClientException
	 * @return integer
	 */
	public function writeId3Tags($path, $strategy = self::ARTIST_TITLE_SEPERATED_BY_MINUS_STRATEGY) {
		$documents = $this->documentRepository->findByHead($path);
		$cntDocuments = count($documents);
		$cntCddb = 0;
		$content = $this->getFileContent($path);
		$lines = explode("\n", $content);
		foreach ($lines AS $line) {
			if ($line !== '') {
				$arr = explode('=', $line);
				if (count($arr) > 1) {
					$key = array_shift($arr);
					$val = implode(self::LINE_DELIMITER, $arr);
					if (strpos($key, self::CDDB_TITLE) !== FALSE) {
						$cntCddb++;
						$track = (int)str_replace(self::CDDB_TITLE, '', $key);
						foreach ($documents as $document) {
							if ($document->getFsTrack() === ($track + 1)) {
								$this->tagByStrategy($document, $strategy, trim($val));
							}
						}
					}
				}
			}
		}
		if ($cntCddb !== $cntDocuments) {
			throw new Exception('cnt missmatch, got ' . $cntCddb . ' cddb documents but ' . $cntDocuments . 'documents', 1436793912);
		}
	}

	/**
	 * @param Document $document
	 * @param $strategy
	 * @param $val
	 * @throws Exception
	 * @return void
	 */
	protected function tagByStrategy(Document $document, $strategy, $val) {
		switch ($strategy) {
			case self::ARTIST_TITLE_SEPERATED_BY_MINUS_STRATEGY:
				$arr = explode(' - ', $val);
				$artist = trim(array_shift($arr));
				$title = trim(implode(' - ', $arr));
				$this->id3TagWriterService->tagDocument($document, 'artist', $artist);
				$this->id3TagWriterService->tagDocument($document, 'title', $title);
				break;
			case self::TITLE_ONY_STRATEGY:
				$this->id3TagWriterService->tagDocument($document, 'title', trim($val));
				break;
			default:
				throw new Exception('unknown strategy ' . $strategy, 1436793911);
		}
	}

	/**
	 * @param $path
	 * @return string
	 * @throws Exception
	 */
	protected function getFileContent($path) {
		try {
			$cddbFile = new \SplFileObject($this->configuration->getMountPoint() . PathService::PATH_DELIMITER . $path . PathService::PATH_DELIMITER . self::FILENAME);
		} catch (\Exception $e) {
			throw new Exception('cddb file not found', 1436720296);
		}
		$content = file_get_contents($cddbFile->getRealPath());
		return $content;
	}


}
