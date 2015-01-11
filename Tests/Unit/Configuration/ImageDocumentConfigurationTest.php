<?php
namespace AchimFritz\Documents\Tests\Unit\Configuration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Configuration\ImageDocumentConfiguration AS Configuration;

/**
 * Testcase for InputDocumentFactory
 */
class ImageDocumentConfigurationTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var array
	 */
	protected $settings = array(
		'imageDocument' => array(
			'webThumbPath' => '1/thumb',
			'webBigPath' => '1/big',
			'webPreviewPath' => '1/preview',
		)
	);

	/**
	 * @test
	 */
	public function getWebThumbPathReturnsWebThumbPathFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('1/thumb', $configuration->getWebThumbPath());
	}

	/**
	 * @test
	 */
	public function getWebBigPathReturnsWebBigPathFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('1/big', $configuration->getWebBigPath());
	}

	/**
	 * @test
	 */
	public function getWebPreviewPathReturnsWebPreviewPathFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('1/preview', $configuration->getWebPreviewPath());
	}

}
