<?php
namespace AchimFritz\Documents\Tests\Unit\Solr;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Solr\InputDocumentFactory;
use AchimFritz\Documents\Domain\Model\ImageDocument;

/**
 * Testcase for InputDocumentFactory
 */
class InputDocumentFactoryTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function addImageFieldsAddsDirectoryName() {
		$factory = $this->getAccessibleMock('AchimFritz\Documents\Solr\InputDocumentFactory', array('foo'));
		$document = $this->getMock('AchimFritz\Documents\Domain\Model\ImageDocument', array('getDirectoryName', 'getMDateTime'));
		$document->expects($this->any())->method('getDirectoryName')->will($this->returnValue('y_m_d'));
		$document->expects($this->any())->method('getMDateTime')->will($this->returnValue(new \DateTime()));
		$configuration = $this->getMock('AchimFritz\Documents\Configuration\ImageDocumentConfiguration');
		$this->inject($document, 'imageDocumentConfiguration', $configuration);
		$inputDocument = new \SolrInputDocument();
		$res = $factory->_call('addImageFields', $document, $inputDocument);
	}

}
?>
