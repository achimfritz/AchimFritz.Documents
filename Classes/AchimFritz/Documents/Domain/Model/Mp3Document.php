<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Mp3Document extends FileSystemDocument {

	/**
	 * @var string
	 */
	protected $fileHash;

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
	 * @return string
	 */
	public function getFsGenre() {
		$pathArr = $this->getPathArr();
		return $pathArr[1];
	}

	/**
	 * @return string
	 */
	public function getFsProvider() {
		$pathArr = $this->getPathArr();
		return $pathArr[0];
	}

	/**
	 * @return string
	 */
	public function getWebThumbPath() {
		$info = $this->getSplFileInfo();
		$parent = $info->getPathInfo();
		$folder = new \SplfileInfo($parent->getRealPath() . '/Folder.jpg');
		if ($folder->isFile()) {
			return $this->getConfiguration()->getWebPath() . '/' . implode('/', $this->getPathArr()) . '/Folder.jpg';
		}
		return '';
	}

	/**
	 * @return string
	 */
	public function getFsAlbum() {
		$genre = $this->getFsGenre();
		if ($genre == 'album') {
			$arr = $this->splitArtistAlbum();
			return $this->insertSpaces($arr[1]);
		} elseif ($genre == 'artist') {
			return 'Unknown';
		} else {
			$pathArr = $this->getPathArr();
			return $this->insertSpaces($pathArr[2]);
		}
	}

	/**
	 * @return string
	 */
	public function getFsArtist() {
		$genre = $this->getFsGenre();
		if ($genre == 'album') {
			$arr = $this->splitArtistAlbum();
			return $this->insertSpaces($arr[0]);
		} elseif ($genre == 'artist') {
			$pathArr = $this->getPathArr();
			return $this->insertSpaces($pathArr[2]);
		} else {
			return 'Various';
		}
	}

	/**
	 * @return string
	 */
	public function getFsTitle() {
		$arr = $this->splitTrackTitle();
		return str_replace('.mp3', '', $this->insertSpaces($arr[1]));
	}

	/**
	 * @return integer
	 */
	public function getFsTrack() {
		$arr = $this->splitTrackTitle();
		return (int)$arr[0];
	}

	/**
	 * @return array
	 */
	public function getPathArr() {
		$arr = explode('/', $this->getName());
		$s = sizeof($arr);
		return array($arr[$s - 4], $arr[$s - 3], $arr[$s - 2]);
	}

	/**
	 * @return array
	 */
	public function splitArtistAlbum() {
		$pathArr = $this->getPathArr();
		$path = $pathArr[2];
		$arr = explode('_', $path);
		return $arr;
	}

	/**
	 * @param string
	 * @return array
	 */
	protected function splitAtUpperCase($s) {
		return preg_split('/(?=[A-Z])/', $s, -1, PREG_SPLIT_NO_EMPTY);
	}

	/**
	 * @param string
	 * @return string
	 */
	protected function insertSpaces($string) {
		return implode(' ', $this->splitAtUpperCase($string));
	}

	/**
	 * @return array
	 */
	protected function splitTrackTitle() {
		$info = $this->getSplFileInfo();
		$path = $info->getBasename();
		$firstTwo = str_split(substr($path, 0, 2));
		if (is_numeric($firstTwo[0])) {
			if (is_numeric($firstTwo[1])) {
				// 2 numbers
				$track = (int)$firstTwo[0] . $firstTwo[1];
				$name = substr($path, 2);
				// remove leading - _
				$name = preg_replace('/^_/', '', $name);
				$name = preg_replace('/^-/', '', $name);
			} else {
				// 1 number
				$track = (int)$firstTwo[0];
				$name = substr($path, 1);
				// remove leading - _
				$name = preg_replace('/^_/', '', $name);
				$name = preg_replace('/^-/', '', $name);
			}
		} else {
			$track = 0;
			$name = $path;
		}
		return array($track, $name);
	}
}
