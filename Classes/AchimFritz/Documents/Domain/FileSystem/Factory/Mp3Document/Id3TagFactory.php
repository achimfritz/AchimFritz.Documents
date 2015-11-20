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
				switch ($key) {
					case 'Time':
						#Time: 05:02     MPEG1, Layer III        [ 160 kb/s @ 44100 Hz - Stereo ]
						$min = trim($assoc[0]);
						$sec  = substr($assoc[1], 0 ,2);
						$bitrate = (int)preg_replace('/.*\[ (.*) kb.*/' , '$1', $line);
						$length = 60 * (int)$min + (int)$sec;
						$id3Tag->setLength($length);
						$id3Tag->setBitrate($bitrate);
						break;
					case 'title':
						#title: foo title        artist: ACDC
						$arr = explode('artist:', implode(':', $assoc));
						$id3Tag->setArtist(trim($arr[1]));
						$id3Tag->setTitle(trim($arr[0]));
						break;
					case 'album':
						#album: High Voltage             year: 1976
						$arr = explode('year:', implode(':', $assoc));
						$id3Tag->setAlbum(trim($arr[0]));
						$id3Tag->setYear((int)trim($arr[1]));
						break;
					case 'track':
						#track: 1                genre: Rock (id 17)
						$arr = explode('genre:', implode(':', $assoc));
						$id3Tag->setTrack((int)trim($arr[0]));
						if (count($arr) > 1) {
							$genre = explode('(id', trim($arr[1]));
							$id3Tag->setGenre(trim($genre[0]));
							if (count($genre) === 2) {
								$id3Tag->setGenreId((int)str_replace(')', '', $genre[1]));
							}
						}
						break;
					default:
						break;
				}
			}
		}
		return $id3Tag;
	}

}
