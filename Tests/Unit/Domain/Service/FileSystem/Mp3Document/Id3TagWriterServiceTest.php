<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService;
use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;

/**
 * Testcase for DocumentExportService
 */
class Id3TagWriterServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception
	 */
	public function tagDocumentCollectionThrowsExceptionForInvalidePathCount() {
		$id3TagWriterService = new Id3TagWriterService();
		$pathService = $this->getMock('AchimFritz\Documents\Domain\Service\PathService', array('splitPaths'));
		$pathService->expects($this->once())->method('splitPaths')->will($this->returnValue(array('foo')));
		$this->inject($id3TagWriterService, 'pathService', $pathService);

		$document = new Mp3Document();
		$category = new Category();
		$documentCollection = new DocumentCollection();
		$documentCollection->setCategory($category);
		$documentCollection->addDocument($document);
		$id3TagWriterService->tagDocumentCollection($documentCollection);
	}

	/**
	 * @test
	 */
	public function tagDocumentCollectionCallsTagDocument() {
		$id3TagWriterService = $this->getMock('AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$id3TagWriterService->expects($this->once())->method('tagDocument');
		$pathService = $this->getMock('AchimFritz\Documents\Domain\Service\PathService', array('splitPaths'));
		$pathService->expects($this->once())->method('splitPaths')->will($this->returnValue(array('foo', 'bar')));
		$this->inject($id3TagWriterService, 'pathService', $pathService);

		$document = new Mp3Document();
		$category = new Category();
		$documentCollection = new DocumentCollection();
		$documentCollection->setCategory($category);
		$documentCollection->addDocument($document);
		$id3TagWriterService->tagDocumentCollection($documentCollection);
	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception
	 */
	public function tagDocumentThrowsExceptionForInvalideTagName() {
		$id3TagWriterService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('foo'));
		$id3TagWriterService->_set('validTagNames', array('artist'));
		$document = new Mp3Document();
		$id3TagWriterService->_call('tagDocument', $document, 'foo', 'bar');
	}

	/**
	 * @test
	 */
	public function tagDocumentCallsLinuxCommandForValideTagName() {
		$id3TagWriterService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('foo'));
		$id3TagWriterService->_set('validTagNames', array('artist'));
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$document->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('foo'));
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository');
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command', array('writeId3Tag'));
		$linuxCommand->expects($this->once())->method('writeId3Tag');
		$this->inject($id3TagWriterService, 'linuxCommand', $linuxCommand);
		$this->inject($id3TagWriterService, 'documentRepository', $documentRepository);
		$id3TagWriterService->_call('tagDocument', $document, 'artist', 'bar');
	}

	/**
	 * @test
	 */
	public function tagDocumentUpdatesDocumentForValideTagName() {
		$id3TagWriterService = $this->getAccessibleMock('AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('foo'));
		$id3TagWriterService->_set('validTagNames', array('artist'));
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getAbsolutePath'));
		$document->expects($this->once())->method('getAbsolutePath')->will($this->returnValue('foo'));
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository', array('update'));
		$documentRepository->expects($this->once())->method('update')->with($document);
		$linuxCommand = $this->getMock('AchimFritz\Documents\Linux\Command');
		$this->inject($id3TagWriterService, 'linuxCommand', $linuxCommand);
		$this->inject($id3TagWriterService, 'documentRepository', $documentRepository);
		$id3TagWriterService->_call('tagDocument', $document, 'artist', 'bar');
	}

}
