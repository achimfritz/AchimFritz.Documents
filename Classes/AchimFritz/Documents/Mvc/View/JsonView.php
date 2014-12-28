<?php
namespace AchimFritz\Documents\Mvc\View;

use TYPO3\Flow\Annotations as Flow;

class JsonView extends \AchimFritz\Rest\Mvc\View\JsonView {

	/**
	 * @var array
	 */
	protected $configuration = array(
		'integrity' => array(
			'_descend' => array(
				'solrDocuments' => array(
					'_descendAll' => array(
					),
				),
				'persistedDocuments' => array(
					'_descendAll' => array(
					),
				),
				'thumbs' => array(
					'_descendAll' => array(
					),
				),
				'filesystemDocuments' => array(
					'_descendAll' => array(
					),
				),
			),
		),
	);

}
