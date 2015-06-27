<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemProperty;

/**
 * Testcase for FileSystemPropertyFactory
 */
class FileSystemPropertyFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function createReturnsFileSystemProperty() {
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getAbsolutePath'));
		$factory = $this->getMock(
			'AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemPropertyFactory',
			array('setExifData', 'setGeeqieMetaData', 'setDimensions', 'setTimestamp')
		);
		$fileSystemProperty = new FileSystemProperty();
		$factory->expects($this->once())->method('setExifData')->will($this->returnValue($fileSystemProperty));
		$factory->expects($this->once())->method('setGeeqieMetaData')->will($this->returnValue($fileSystemProperty));
		$factory->expects($this->once())->method('setDimensions')->will($this->returnValue($fileSystemProperty));
		$factory->expects($this->once())->method('setTimestamp')->will($this->returnValue($fileSystemProperty));
		$factory->create($document);
	}
	/**
	 * @test
	 */
	public function setExifDataSetsExifData() {
		$factory = $this->getAccessibleMock(
			'AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemPropertyFactory',
			array('foo')
		);
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getAbsolutePath'));
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('getExifData'));
		$linuxCommand->expects($this->once())->method('getExifData')->will($this->returnValue('foo'));
		$this->inject($factory, 'linuxCommand', $linuxCommand);
		$fileSystemProperty = new FileSystemProperty();
		$fileSystemProperty = $factory->_call('setExifData', $document, $fileSystemProperty);
		$this->assertSame('foo', $fileSystemProperty->getExifData());
	}
}
