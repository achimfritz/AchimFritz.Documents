<?php
namespace AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

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
		return $id3Tag;
	}

}
