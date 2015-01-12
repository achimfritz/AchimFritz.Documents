<?php
namespace AchimFritz\Documents\Tests\Unit\Configuration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Configuration\Mp3DocumentConfiguration AS Configuration;

/**
 * Testcase for InputDocumentFactory
 */
class Mp3DocumentConfigurationTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var array
	 */
	protected $settings = array(
		'mp3Document' => array(
			'mountPoint' => '/mp3',
		)
	);

	/**
	 * @test
	 */
	public function getMountPointReturnsMountPointFromSettings() {
		$configuration = new Configuration();
		$this->inject($configuration, 'settings', $this->settings);
		$this->assertSame('/mp3', $configuration->getMountPoint());
	}

}
