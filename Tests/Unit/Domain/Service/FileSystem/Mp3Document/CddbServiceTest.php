<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */


use AchimFritz\Documents\Domain\Model\Mp3Document as Document;
use AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\Cddb;

/**
 * Testcase for CddbServiceTest
 */
class CddbServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	protected $validContent = 'TTITLE1=John Travolta - Royale With = Cheese
DTITLE=Soundtrack / Pulp Fiction
DYEAR=1994
DGENRE=Soundtrack';

	/**
	 * @test
	 */
	public function writeId3TagsTagOneDocument() {
		$cddb = new Cddb();
		$cddb->setPath('foo');
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository', array('findByHead'));
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getFsTrack'));
		$documentRepository->expects($this->once())->method('findByHead')->will($this->returnValue(array($document)));
		$document->expects($this->any())->method('getFsTrack')->will($this->returnValue(2));

		$cddbService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('getFileContent', 'tagByFormat'));
		$cddbService->expects($this->once())->method('getFileContent')->will($this->returnValue($this->validContent));
		$cddbService->expects($this->once())->method('tagByFormat')->with($document, Cddb::TITLE_FORMAT, 'John Travolta - Royale With = Cheese');

		$this->inject($cddbService, 'documentRepository', $documentRepository);
		$id3TagWriterService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$this->inject($cddbService, 'id3TagWriterService', $id3TagWriterService);

		$cddbService->writeId3Tags($cddb);

	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception
	 */
	public function writeId3TagsThrowsExceptionIfCountDiffer() {
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository', array('findByHead'));
		$documentRepository->expects($this->once())->method('findByHead')->will($this->returnValue(array()));

		$cddbService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('getCddbContent', 'tagByFormat'));
		$cddbService->expects($this->once())->method('getCddbContent')->will($this->returnValue('TTITLE1=John Travolta - Royale With = Cheese' . "\n"));

		$this->inject($cddbService, 'documentRepository', $documentRepository);

		$cddb = new Cddb();
		$cddb->setPath('foo');
		$cddbService->writeId3Tags($cddb);
	}

	/**
	 * @test
	 */
	public function tagByFormatTagsArtistAndTitleForArtistTitleSeperatedByMinusStrategy() {
		$cddbService = $this->getAccessibleMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('foo'));
		$document = new Document();
		$id3TagWriterService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$id3TagWriterService->expects($this->at(0))->method('tagDocument')->with($document, 'artist', 'John Travolta');
		$id3TagWriterService->expects($this->at(1))->method('tagDocument')->with($document, 'title', 'Royale With =- Cheese');
		$this->inject($cddbService, 'id3TagWriterService', $id3TagWriterService);
		$cddbService->_call('tagByFormat', $document, Cddb::ARTIST_TITLE_FORMAT, 'John Travolta - Royale With =- Cheese');
	}

	/**
	 * @test
	 */
	public function tagByFormatTagsTitleForTitleOnlyStrategy() {
		$cddbService = $this->getAccessibleMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('foo'));
		$document = new Document();
		$id3TagWriterService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$id3TagWriterService->expects($this->once())->method('tagDocument')->with($document, 'title', 'Royale With =- Cheese');
		$this->inject($cddbService, 'id3TagWriterService', $id3TagWriterService);
		$cddbService->_call('tagByFormat', $document, Cddb::TITLE_FORMAT, 'Royale With =- Cheese');
	}

}
