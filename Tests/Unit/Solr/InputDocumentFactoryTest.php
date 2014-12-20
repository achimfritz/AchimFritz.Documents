<?php
namespace AchimFritz\Documents\Tests\Unit\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Solr\InputDocumentFactory;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystem;

/**
 * Testcase for InputDocumentFactory
 */
class InputDocumentFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function addImageFieldsAddsDirectoryName() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Solr\InputDocumentFactory', array('foo'));
		$fileSystemFactory = $this->getMock('AchimFritz\Documents\Domain\Model\Facet\ImageDocument\FileSystemFactory', array('create'));
		$fileSystemFactory->expects($this->once())->method('create')->will($this->returnValue(new FileSystem()));
		$this->inject($factory, 'imageFileSystemFactory', $fileSystemFactory);
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getDirectoryName'));
		$document->expects($this->any())->method('getDirectoryName')->will($this->returnValue('y_m_d'));
		$inputDocument = new \SolrInputDocument();
		$res = $factory->_call('addImageFields', $document, $inputDocument);
	}

}
?>
