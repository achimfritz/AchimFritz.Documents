<?php
namespace AchimFritz\Documents\Tests\Unit\Configuration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration AS Configuration;

/**
 * Testcase for InputDocumentFactory
 */
class FileSystemDocumentConfigurationTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var array
	 */
	protected $settings = array(
		'fileSystemDocument' => array(
			'mountPoint' => '/foo/bar',
			'webPath' => '1/2',
		)
	);

	/**
	 * @test
	 */
	public function getWebPathReturnsWebPathFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('1/2', $configuration->getWebPath());
	}

	/**
	 * @test
	 */
	public function getMountPointReturnsMountPointFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('/foo/bar', $configuration->getMountPoint());
	}

}
