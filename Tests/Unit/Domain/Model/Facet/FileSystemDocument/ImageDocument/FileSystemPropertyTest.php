<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemProperty;

/**
 * Testcase for FileSystemProperty
 */
class FileSystemPropertyTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function getValueReturnsValue() {
		$fileSystemProperty = new FileSystemProperty();
		$fileSystemProperty->setExifData('foo	bar');
		$val = $fileSystemProperty->getExifValue('foo');
		$this->assertSame('bar', $val);
	}

	/**
	 * @test
	 */
	public function getValueReturnsEmptyStringIfNotFound() {
		$fileSystemProperty = new FileSystemProperty();
		$fileSystemProperty->setExifData('baz	bar');
		$val = $fileSystemProperty->getExifValue('foo');
		$this->assertSame('', $val);
	}
}
