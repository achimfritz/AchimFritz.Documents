<?php
namespace AchimFritz\Documents\Domain\Facet;

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
	 * @var string
	 */
	protected $artist;

	/**
	 * @var string
	 */
	protected $album;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var integer
	 */
	protected $track;

	/**
	 * @var string
	 */
	protected $genre;

	/**
	 * @var integer
	 */
	protected $genreId;


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
}
