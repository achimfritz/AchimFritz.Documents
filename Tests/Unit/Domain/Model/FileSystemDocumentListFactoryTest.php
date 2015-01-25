<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\FileSystemDocumentListFactory;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;
use org\bovigo\vfs\vfsStream;

/**
 * Testcase for FileSystemDocument
 */
class FileSystemDocumentListFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var \org\bovigo\vfs\vfsStreamFile
	 */
	protected $inputFile;

	/**
	 * @return void
	 */
	public function setUp() {
		$root = vfsStream::setup('root');
		$this->inputFile = vfsStream::newFile('inputFile');
		$this->inputFile->setContent('/mp/folder/test.txt');
		$root->addChild($this->inputFile);
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Model\Exception
	 */
	public function createFromFileThrowsExceptionIfFileNotExists() {	
		$factory = new FileSystemDocumentListFactory();
		$documentList = $factory->createFromFile('foo');
	}

	/**
	 * @test
	 */
	public function createFromFileReturnsList() {
		$document = new FileSystemDocument();
		$factory = $this->getMock('AchimFritz\Documents\Domain\Model\FileSystemDocumentListFactory', array('getDocument'));
		$factory->expects($this->once())->method('getDocument')->will($this->returnValue($document));
		$documentList = $factory->createFromFile('vfs://root/inputFile');
		$items = $documentList->getDocumentListItems();
		$this->assertSame(1, count($items));
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Model\Exception
	 */
	public function getDocumentThrowsExceptionIfDocumentIsNotFound() {
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\FileSystemDocumentRepository', array('findOneByName'));
		$documentRepository->expects($this->once())->method('findOneByName')->will($this->returnValue(NULL));
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\FileSystemDocumentListFactory', array('getNameFromAbsolutePath'));
		$this->inject($factory, 'documentRepository', $documentRepository);
		$document = $factory->_call('getDocument', 'foo');
	}

	/**
	 * @test
	 */
	public function getNameFromAbsolutePathRemovesMountPoint() {
		$pathService = new \AchimFritz\Documents\Domain\Service\PathService();
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration', array('getMountPoint'));
		$configuration->expects($this->once())->method('getMountPoint')->will($this->returnValue('/mp'));
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Domain\Model\FileSystemDocumentListFactory', array('foo'));
		$this->inject($factory, 'pathService', $pathService);
		$this->inject($factory, 'configuration', $configuration);
		$name = $factory->_call('getNameFromAbsolutePath', '/mp/root/inputFile');
		$this->assertSame('root/inputFile', $name);
	}

}
