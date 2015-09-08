<?php
namespace AchimFritz\Documents\Domain\FileSystem\Service\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Id3Tag;
use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Mp3Document as Document;
use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Cddb;

/**
 * @Flow\Scope("singleton")
 */
class CddbService {

	const FILENAME = 'Cddb.txt';
	const ARTIST_ALBUM_DELIMITER = ' / ';
	const ARTIST_TITLE_DELIMITER = ' / ';
	const CDDB_TITLE = 'TTITLE';
	const CDDB_ARTIST_ALBUM = 'DTITLE';
	const CDDB_YEAR = 'DYEAR';
	const CDDB_GENRE = 'DGENRE';
	const LINE_DELIMITER = '=';

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Id3TagFactory
	 * @Flow\Inject
	 */
	protected $idTagFactory;

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
	 * @param Cddb $cddb
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @throws \SolrClientException
	 * @return integer
	 */
	public function writeCddbFile(Cddb $cddb) {
		$path = $cddb->getPath();
		$format = $cddb->getFormat();
		$documents = $this->documentRepository->findByHead($path);
		$cddbFileName = $this->getCddbFileName($path);
		$cnt = 0;
		$content = array();
		foreach ($documents as $document) {
			$id3Tag = $this->idTagFactory->create($document);
			if ($cnt === 0) {
				$artist = $this->getBestProperty('Artist', $document, $id3Tag);
				$album = $this->getBestProperty('Album', $document, $id3Tag);
				if ($format === Cddb::TITLE_FORMAT) {
					$content[] = self::CDDB_ARTIST_ALBUM . self::LINE_DELIMITER . $artist . self::ARTIST_ALBUM_DELIMITER . $album;
				} else {
					$content[] = self::CDDB_ARTIST_ALBUM . self::LINE_DELIMITER . $album;
				}
				$content[] = self::CDDB_YEAR . self::LINE_DELIMITER . $id3Tag->getYear();
				$content[] = self::CDDB_GENRE . self::LINE_DELIMITER . $id3Tag->getGenre();
			}
			$track = (int)$this->getBestProperty('Track', $document, $id3Tag, TRUE) - 1;
			if ($track < 0) {
				throw new Exception('no track number for ' . $document->getName(), 1437564851);
			}
			$title = $this->getBestProperty('Title', $document, $id3Tag);
			if ($format === Cddb::TITLE_FORMAT) {
				$content[] = self::CDDB_TITLE . $track . self::LINE_DELIMITER . $title;
			} else {
				$artist = $this->getBestProperty('Artist', $document, $id3Tag);
				$content[] = self::CDDB_TITLE . $track . self::LINE_DELIMITER . $artist . self::ARTIST_TITLE_DELIMITER . $title;
			}
			$cnt++;
		}
		if (@file_put_contents($cddbFileName, implode("\n", $content) . "\n") === FALSE) {
			throw new Exception('cannot write file ' . $cddbFileName, 1437564850);
		}
		return $cnt;
	}

	/*
	 * @param Cddb $cddb
	 * @throws Exception
	 * @throws \AchimFritz\Documents\Linux\Exception
	 * @return integer
	 */
	public function writeId3Tags(Cddb $cddb) {
		$artist = $album = $genre = $year = '';
		$path = $cddb->getPath();
		$format = $cddb->getFormat();
		$documents = $this->documentRepository->findByHead($path);
		$cntDocuments = count($documents);
		$cntCddb = 0;
		$content = $this->getCddbContent($cddb);
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
								$this->id3TagWriterService->removeTags($document);
								$this->tagByFormat($document, $format, trim($val));
								$this->id3TagWriterService->tagDocument($document, 'track', $document->getFsTrack());
							}
						}
					} elseif (strpos($key, self::CDDB_ARTIST_ALBUM) !== FALSE) {
						if ($format === Cddb::TITLE_FORMAT) {
							$arr = explode(self::ARTIST_ALBUM_DELIMITER, $val);
							if (count($arr) < 1) {
								throw new Exception('cannot split artist album for ' . $val, 1437307738);
							}
							$artist = trim(array_shift($arr));
							$album = trim(implode(self::CDDB_GENRE, $arr));
						} else {
							$album = trim($val);
						}
					} elseif (strpos($key, self::CDDB_GENRE) !== FALSE) {
						$genre = trim($val);
					} elseif (strpos($key, self::CDDB_YEAR) !== FALSE) {
						$year = trim($val);
					}

				}
			}
		}
		if ($cntCddb !== $cntDocuments) {
			throw new Exception('cnt missmatch, got ' . $cntCddb . ' cddb documents but ' . $cntDocuments . ' documents', 1436793912);
		}
		if (($artist === '' && $format === Cddb::TITLE_FORMAT) || $album === '' || $genre === '' || $year === '') {
			throw new Exception ('empty values in artist, album, year or genre', 1437307739);
		}
		foreach ($documents as $document) {
			if ($format === Cddb::TITLE_FORMAT) {
				$this->id3TagWriterService->tagDocument($document, 'artist', $artist);
			}
			$this->id3TagWriterService->tagDocument($document, 'album', $album);
			$this->id3TagWriterService->tagDocument($document, 'genre', $genre);
			$this->id3TagWriterService->tagDocument($document, 'year', $year);
		}
	}


	/**
	 * @param $name
	 * @param Document $document
	 * @param Id3Tag $id3Tag
	 * @param bool $intCompare
	 * @return string
	 */
	protected function getBestProperty($name, Document $document, Id3Tag $id3Tag, $intCompare = FALSE) {
		$id3TagMethod = 'get' . $name;
		$fsTagMethod = 'getFs' . $name;
		if ($intCompare === TRUE) {
			if ($id3Tag->$id3TagMethod() > 0) {
				$property = $id3Tag->$id3TagMethod();
			} else {
				$property = $document->$fsTagMethod();
			}
		} else {
			if ($id3Tag->$id3TagMethod() !== '') {
				$property = $id3Tag->$id3TagMethod();
			} else {
				$property = $document->$fsTagMethod();
			}
		}
		return $property;
	}

	/**
	 * @param Cddb $cddb
	 * @return void
	 */
	protected function getCddbContent(Cddb $cddb) {
		if ($cddb->getUrl() !== '') {
			$this->fetchUrlContent($cddb);
		}
		return $this->getFileContent($cddb->getPath());
	}

	/**
	 * @param Cddb $cddb
	 * @return void
	 */
	protected function fetchUrlContent(Cddb $cddb) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $cddb->getUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		if(curl_errno($ch)) {
			$errorCode = curl_errno($ch);
			curl_close($ch);
			throw new Exception('curl not success for url ' . $cddb->getUrl() . ' with error code ' . $errorCode, 1437307735);

		}
		curl_close($ch);
		$cddbFileName = $this->getCddbFileName($cddb->getPath());
		$content = mb_convert_encoding($content, 'UTF-8', "ISO-8859-1");
		if (@file_put_contents($cddbFileName, $content) === FALSE) {
			throw new Exception('cannot write file . ' . $cddbFileName, 1437307737);
		}
	}

	/**
	 * @param Document $document
	 * @param $strategy
	 * @param $val
	 * @throws Exception
	 * @return void
	 */
	protected function tagByFormat(Document $document, $format, $val) {
		switch ($format) {
			case Cddb::ARTIST_TITLE_FORMAT:
				$arr = explode(self::ARTIST_TITLE_DELIMITER, $val);
				$artist = trim(array_shift($arr));
				$title = trim(implode(self::ARTIST_TITLE_DELIMITER, $arr));
				if ($title === '') {
					throw new Exception('title is empty for ' . $val, 1436793921);
				}
				$this->id3TagWriterService->tagDocument($document, 'artist', $artist);
				$this->id3TagWriterService->tagDocument($document, 'title', $title);
				break;
			case Cddb::TITLE_FORMAT:
				$this->id3TagWriterService->tagDocument($document, 'title', trim($val));
				break;
			default:
				throw new Exception('unknown strategy ' . $format, 1436793911);
		}
	}

	/**
	 * @param $path
	 * @return string
	 */
	protected function getCddbFileName($path) {
		return $this->configuration->getMountPoint() . PathService::PATH_DELIMITER . $path . PathService::PATH_DELIMITER . self::FILENAME;
	}

	/**
	 * @param $path
	 * @return string
	 * @throws Exception
	 */
	protected function getFileContent($path) {
		try {
			$cddbFile = new \SplFileObject($this->getCddbFileName($path));
		} catch (\Exception $e) {
			throw new Exception('cddb file not found', 1436720296);
		}
		$content = file_get_contents($cddbFile->getRealPath());
		return $content;
	}


}
