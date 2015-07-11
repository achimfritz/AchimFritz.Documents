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
class SolrSettingsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

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
	 * @param string $servlet
	 * @return string
	 */
	protected function render($servlet='select') {
		$solrSettings = $this->settings['solr'];
		$solrSettings['servlet'] = $servlet;
		return json_encode($solrSettings);
	}

}
