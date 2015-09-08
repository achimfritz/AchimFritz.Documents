<?php
namespace AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document\Id3Tag;
use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document;

/**
 * @Flow\Scope("singleton")
 */
class Id3TagFactory {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param Mp3Document $mp3Document 
	 * @return Id3Tag
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	public function create(Mp3Document $mp3Document) {
		$id3Tag = new Id3Tag();
		$file = $mp3Document->getAbsolutePath();
		$res = $this->linuxCommand->readId3Tags($file);
		foreach ($res AS $line) {
			$assoc = explode(':', $line);
			if (count($assoc) > 1) {
				$key = trim(array_shift($assoc));
				$val = trim(implode(':', $assoc));
				switch ($key) {
					case 'Album':
						$id3Tag->setAlbum($val);
						break;
					case 'Genre':
						$arr = explode('(', $val);
						$id3Tag->setGenre(trim($arr[0]));
						if (count($arr) === 2) {
							$id3Tag->setGenreId((int)str_replace(')', '', $arr[1]));
						}
						break;
					case 'Track':
						$id3Tag->setTrack((int)$val);
						break;
					case 'Year':
						$id3Tag->setYear((int)$val);
						break;
					case 'Artist':
						$id3Tag->setArtist($val);
						break;
					case 'Title':
						$id3Tag->setTitle($val);
						break;
					default:
						break;
				}
			}
		}
		// TODO !!!
		#$id3Tag = $this->setLength($id3Tag, $file);
		return $id3Tag;
	}

	/**
	 * @param Id3Tag $id3Tag
	 * @param $file
	 * @return Id3Tag
	 * @throws \AchimFritz\Documents\Linux\Exception
	 */
	protected function setLength(Id3Tag $id3Tag, $file) {
		$res = $this->linuxCommand->readId3Tags($file, TRUE);
		if (count($res) > 0) {
			try {
				$xml = new \SimpleXMLElement(implode('', $res));
				$id3Tag->setLength((int)$xml->length);
			} catch (\Exception $e) {
				throw new \AchimFritz\Documents\Linux\Exception('cannot create SimpleXMLElement on ' . $file, 1440599809);
			}
		}
		return $id3Tag;
	}

}
