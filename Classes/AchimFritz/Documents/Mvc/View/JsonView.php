<?php
namespace AchimFritz\Documents\Mvc\View;

use TYPO3\Flow\Annotations as Flow;

class JsonView extends \AchimFritz\Rest\Mvc\View\JsonView {

	/**
	 * @var array
	 */
	protected $configuration = array(
		'mp3Document' => array(
			'_exclude' => array('allCategories', 'category')
		),
		'imageDocument' => array(
			'_exclude' => array('allCategories', 'category')
		),
		'document' => array(
			'_exclude' => array('allCategories', 'category')
		),
		'documentLists' => array(
			'_descendAll' => array(
				'_descend' => array(
					'category' => array(
						'_only' => array('path')
					)
				),
				'_exclude' => array('documentListItems', 'document', 'documentListItem')
			),
		),
		'documentList' => array(
				'_descend' => array(
					'documentListItems' => array(
						'_descendAll' => array(
							'_descend' => array(
								'document' => array(
									'_exclude' => array('allCategories', 'category')
								)
							)
						),
					)
				),
			'_exclude' => array('document', 'documentListItem')
		),
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
