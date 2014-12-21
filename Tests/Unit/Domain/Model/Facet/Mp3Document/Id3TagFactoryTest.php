<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3Tag;
use AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory;
use AchimFritz\Documents\Linux\Command;
use AchimFritz\Documents\Domain\Model\Mp3Document;

/**
 * Testcase for FileSystemDocument
 */
class Id3TagFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {


	/**
	 * @return array
	 */
	protected function getEyeD3Out() {
		$eyeD3Out = '
Filename: 01-Im Ascheregen.mp3
Artist: Casper
Album: Hinterland
Genre: Rap (15)
Title: Im Ascheregen
Track: 1
Year: 2013

';
		return explode("\n", $eyeD3Out);
	}


	/**
	 * @test
	 */
	public function createSetsId3TagAlbum() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Hinterland', $id3Tag->getAlbum());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagGenreId() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(15, $id3Tag->getGenreId());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagGenre() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Rap', $id3Tag->getGenre());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagTitle() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Im Ascheregen', $id3Tag->getTitle());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagYear() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(2013, $id3Tag->getYear());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagTrack() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame(1, $id3Tag->getTrack());
	}

	/**
	 * @test
	 */
	public function createSetsId3TagArtist() {	
		$document = new Mp3Document();
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('readId3Tags'));
		$linuxCommand->expects($this->once())->method('readId3Tags')->will($this->returnValue($this->getEyeD3Out()));
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\Id3TagFactory', array('getAbsolutePath'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$id3Tag = $factory->create($document);
		$this->assertSame('Casper', $id3Tag->getArtist());
	}

}
