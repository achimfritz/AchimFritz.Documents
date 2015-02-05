<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class CategoryCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 * @Flow\Inject
	 */
	protected $categoryRepository;

	/**
	 * @access public
	 * @return void
	 */
	public function listCommand() {
		$categories = $this->categoryRepository->findAll();
		if (count($categories) === 0) {
			$this->outputLine('WARNING: no categories found');
			$this->quit();
		}
		foreach ($categories AS $category) {
			$this->outputLine($category->getPath());
		}
	}
}
