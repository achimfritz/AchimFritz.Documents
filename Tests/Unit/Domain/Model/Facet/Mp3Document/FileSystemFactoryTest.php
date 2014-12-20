<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\Facet\Mp3Document\FileSystem;
use AchimFritz\Documents\Domain\Model\Facet\Mp3Document\FileSystemFactory;

/**
 * Testcase for InputDocumentFactory
 */
class FileSystemFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	protected $settings = array(
		'mp3Document' => array(
			'mountPoint' => '/foo/bar',
			'webPath' => '1/2',
		)
	);

	/**
	 * @test
	 */
	public function createSetsWebPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new Mp3Document();
		$fileSystem = $factory->create($document);
		$this->assertSame('1/2', $fileSystem->getWebPath());
	}

	/**
	 * @test
	 */
	public function createSetsMountPoint() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\Mp3Document\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new Mp3Document();
		$fileSystem = $factory->create($document);
		$this->assertSame('/foo/bar', $fileSystem->getMountPoint());
	}

}
?>
