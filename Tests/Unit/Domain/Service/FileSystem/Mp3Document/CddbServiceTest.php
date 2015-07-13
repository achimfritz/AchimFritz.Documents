<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */


use AchimFritz\Documents\Domain\Model\Mp3Document as Document;
use AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService;

/**
 * Testcase for CddbServiceTest
 */
class CddbServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function writeId3TagsTagOneDocument() {
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository', array('findByHead'));
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\Mp3Document', array('getFsTrack'));
		$documentRepository->expects($this->once())->method('findByHead')->will($this->returnValue(array($document)));
		$document->expects($this->once())->method('getFsTrack')->will($this->returnValue(2));

		$cddbService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('getFileContent', 'tagByStrategy'));
		$cddbService->expects($this->once())->method('getFileContent')->will($this->returnValue('TTITLE1=John Travolta - Royale With = Cheese' . "\n"));
		$cddbService->expects($this->once())->method('tagByStrategy')->with($document, CddbService::ARTIST_TITLE_SEPERATED_BY_MINUS_STRATEGY, 'John Travolta - Royale With = Cheese');

		$this->inject($cddbService, 'documentRepository', $documentRepository);

		$cddbService->writeId3Tags('foo');

	}

	/**
	 * @test
	 * @expectedException \AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Exception
	 */
	public function writeId3TagsThrowsExceptionIfCountDiffer() {
		$documentRepository = $this->getMock('AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository', array('findByHead'));
		$documentRepository->expects($this->once())->method('findByHead')->will($this->returnValue(array()));

		$cddbService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('getFileContent', 'tagByStrategy'));
		$cddbService->expects($this->once())->method('getFileContent')->will($this->returnValue('TTITLE1=John Travolta - Royale With = Cheese' . "\n"));

		$this->inject($cddbService, 'documentRepository', $documentRepository);

		$cddbService->writeId3Tags('foo');
	}

	/**
	 * @test
	 */
	public function tagByStrategyTagsArtistAndTitleForArtistTitleSeperatedByMinusStrategy() {
		$cddbService = $this->getAccessibleMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('foo'));
		$document = new Document();
		$id3TagWriterService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$id3TagWriterService->expects($this->at(0))->method('tagDocument')->with($document, 'artist', 'John Travolta');
		$id3TagWriterService->expects($this->at(1))->method('tagDocument')->with($document, 'title', 'Royale With =- Cheese');
		$this->inject($cddbService, 'id3TagWriterService', $id3TagWriterService);
		$cddbService->_call('tagByStrategy', $document, CddbService::ARTIST_TITLE_SEPERATED_BY_MINUS_STRATEGY, 'John Travolta - Royale With =- Cheese');
	}

	/**
	 * @test
	 */
	public function tagByStrategyTagsTitleForTitleOnlyStrategy() {
		$cddbService = $this->getAccessibleMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\CddbService', array('foo'));
		$document = new Document();
		$id3TagWriterService = $this->getMock('\AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document\Id3TagWriterService', array('tagDocument'));
		$id3TagWriterService->expects($this->once())->method('tagDocument')->with($document, 'title', 'Royale With =- Cheese');
		$this->inject($cddbService, 'id3TagWriterService', $id3TagWriterService);
		$cddbService->_call('tagByStrategy', $document, CddbService::TITLE_ONY_STRATEGY, 'Royale With =- Cheese');
	}

}
