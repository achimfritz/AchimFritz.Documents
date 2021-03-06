<?php
namespace AchimFritz\Documents\Domain\FileSystem\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class Id3Tag {

	/**
	 * @var integer
	 */
	protected $length = 0;

	/**
	 * @var integer
	 */
	protected $bitrate = 0;

	/**
	 * @var string
	 */
	protected $artist = '';

	/**
	 * @var string
	 */
	protected $album = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var integer
	 */
	protected $track = 0;

	/**
	 * @var string
	 */
	protected $genre = '';

	/**
	 * @var integer
	 */
	protected $genreId = 0;

	/**
	 * @var integer
	 */
	protected $year = 0;

	/**
	 * @return string
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param string $year
	 * @return void
	 */
	public function setYear($year) {
		$this->year = $year;
	}


	/**
	 * @return string
	 */
	public function getArtist() {
		return $this->artist;
	}

	/**
	 * @param string $artist
	 * @return void
	 */
	public function setArtist($artist) {
		$this->artist = $artist;
	}

	/**
	 * @return string
	 */
	public function getAlbum() {
		return $this->album;
	}

	/**
	 * @param string $album
	 * @return void
	 */
	public function setAlbum($album) {
		$this->album = $album;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return integer
	 */
	public function getTrack() {
		return $this->track;
	}

	/**
	 * @param integer $track
	 * @return void
	 */
	public function setTrack($track) {
		$this->track = $track;
	}

	/**
	 * @return string
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * @param string $genre
	 * @return void
	 */
	public function setGenre($genre) {
		$this->genre = $genre;
	}

	/**
	 * @return integer
	 */
	public function getGenreId() {
		return $this->genreId;
	}

	/**
	 * @param integer $genreId
	 * @return void
	 */
	public function setGenreId($genreId) {
		$this->genreId = $genreId;
	}

	/**
	 * @return string
	 */
	public function getArtistLetter() {
		return strtoupper(mb_substr($this->getArtist(), 0, 1, 'UTF-8'));
	}

	/**
	 * @return int
	 */
	public function getLength() {
		return $this->length;
	}

	/**
	 * @param int $length
	 */
	public function setLength($length) {
		$this->length = $length;
	}

	/**
	 * @return int
	 */
	public function getBitrate() {
		return $this->bitrate;
	}

	/**
	 * @param int $bitrate
	 */
	public function setBitrate($bitrate) {
		$this->bitrate = $bitrate;
	}

}
