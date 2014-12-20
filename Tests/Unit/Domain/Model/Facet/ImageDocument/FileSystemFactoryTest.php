<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystem;
use AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory;

/**
 * Testcase for InputDocumentFactory
 */
class FileSystemFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	protected $settings = array(
		'imageDocument' => array(
			'mountPoint' => '/foo/bar',
			'webPath' => '1/2',
			'webPreviewPath' => '1/3',
			'webThumbPath' => '1/4',
			'webBigPath' => '1/5',
		)
	);

	/**
	 * @test
	 */
	public function createSetsWebPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('1/2', $fileSystem->getWebPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebPreviewPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('1/3', $fileSystem->getWebPreviewPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebBigPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('1/5', $fileSystem->getWebBigPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebThumbPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('1/4', $fileSystem->getWebThumbPath());
	}

	/**
	 * @test
	 */
	public function createSetsMountPoint() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('/foo/bar', $fileSystem->getMountPoint());
	}

}
?>
