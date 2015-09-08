<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\FileSystemProperty;

/**
 * Testcase for FileSystemProperty
 */
class FileSystemPropertyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getValueReturnsValue() {
		$fileSystemProperty = new FileSystemProperty();
		$fileSystemProperty->setExifData(array('foo	bar'));
		$val = $fileSystemProperty->getExifValue('foo');
		$this->assertSame('bar', $val);
	}

	/**
	 * @test
	 */
	public function getValueReturnsEmptyStringIfNotFound() {
		$fileSystemProperty = new FileSystemProperty();
		$fileSystemProperty->setExifData(array('baz	bar'));
		$val = $fileSystemProperty->getExifValue('foo');
		$this->assertSame('', $val);
	}
}
