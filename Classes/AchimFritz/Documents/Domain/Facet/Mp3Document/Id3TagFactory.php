<?php
namespace AchimFritz\Documents\Domain\Facet\Mp3Document;

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
	 */
	public function create(Mp3Document $mp3Document) {
		$id3Tag = new Id3Tag();
		$file = $mp3Document->getAbsolutePath();
		$res = $this->linuxCommand->readId3Tags($file);
		foreach ($res AS $line) {
			$assoc = explode(':', $line);
			if (count($assoc) === 2) {
				$key = trim($assoc[0]);
				$val = trim($assoc[1]);
				switch ($key) {
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
