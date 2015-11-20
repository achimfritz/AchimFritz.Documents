<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\FileSystem\Factory\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */


use AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory;

/**
 * Testcase for FileSystemDocument
 */
class Id3TagFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @return array
	 */
	protected function getEyeD3Outrfc822() {
		$eyeD3Out = '
Filename: 01-Im Ascheregen.mp3
Artist: Casper
Album: Hinterland
Genre: Rap (15)
Title: Im Ascheregen:test
Track: 1
Year: 2013

';
		return explode("\n", $eyeD3Out);
	}

	/**
	 * @return array
	 */
	protected function getEyeD3Out() {
		$eyeD3Out = '
01ItSalongwaytothetopIfyouwannarockNRoll.mp3    [ 5.76 MB ]
-------------------------------------------------------------------------------
Time: 05:02     MPEG1, Layer III        [ 160 kb/s @ 44100 Hz - Stereo ]
-------------------------------------------------------------------------------
ID3 v2.4:
title: Im Ascheregen:test        artist: Casper
album: Hinterland             year: 2013
track: 1                genre: Rap (id 15)

';
		return explode("\n", $eyeD3Out);
	}

	/**
	 * @test
	 */
	public function createSetsId3TagBitrate() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(160, $id3Tag->getBitrate());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagLength() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(302, $id3Tag->getLength());
	}


	/**
	 * @test
	 */
	public function createSetsId3TagAlbum() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Hinterland', $id3Tag->getAlbum());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagGenreId() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(15, $id3Tag->getGenreId());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagGenre() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Rap', $id3Tag->getGenre());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagTitle() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Im Ascheregen:test', $id3Tag->getTitle());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagYear() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(2013, $id3Tag->getYear());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagTrack() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(1, $id3Tag->getTrack());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagArtist() {	
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\FileSystem\Factory\Mp3Document\Id3TagFactory', array('setLength'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Casper', $id3Tag->getArtist());
	}

}
