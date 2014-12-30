<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model\Facet\FileSystemDocument\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystem;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory;

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
	public function setAbsoluteWebThumbPathSetsWebPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('getFlowPathWeb'));
		$factory->expects($this->once())->method('getFlowPathWeb')->will($this->returnValue('/var/www/'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$document->setName('foo');
		$fileSystem = $factory->create($document);
		$this->assertSame('/var/www/1/2/foo', $fileSystem->getAbsoluteWebThumbPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$document->setName('foo');
		$fileSystem = $factory->create($document);
		$this->assertSame('1/2/foo', $fileSystem->getWebPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebPreviewPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$document->setName('foo');
		$fileSystem = $factory->create($document);
		$this->assertSame('1/3/foo', $fileSystem->getWebPreviewPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebBigPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$document->setName('foo');
		$fileSystem = $factory->create($document);
		$this->assertSame('1/5/foo', $fileSystem->getWebBigPath());
	}

	/**
	 * @test
	 */
	public function createSetsWebThumbPath() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$document->setName('foo');
		$fileSystem = $factory->create($document);
		$this->assertSame('1/4/foo', $fileSystem->getWebThumbPath());
	}

	/**
	 * @test
	 */
	public function createSetsMountPoint() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory', array('foo'));
		$factory->_set('settings', $this->settings);
		$document = new ImageDocument();
		$fileSystem = $factory->create($document);
		$this->assertSame('/foo/bar', $fileSystem->getMountPoint());
	}

}
?>
