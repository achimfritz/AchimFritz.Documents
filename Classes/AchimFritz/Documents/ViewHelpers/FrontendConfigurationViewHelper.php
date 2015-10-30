<?php
namespace AchimFritz\Documents\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 *
 * @Flow\Scope("singleton")
 */
class FrontendConfigurationViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	protected function render() {
		$configuration = array(
			'solrSettings' => $this->settings['solr'],
			'applicationRoot' => FLOW_PATH_ROOT
		);
		return json_encode($configuration);
	}

}
