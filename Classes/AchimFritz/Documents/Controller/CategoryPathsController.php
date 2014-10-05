<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Object\ObjectManager;

/**
 * Category controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
class CategoryPathsController extends AbstractCategoryController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'paths';

	/**
	 * Updates the given paths
	 *
	 * @param array $paths
	 * @return void
	 */
	public function updateAction(array $paths) {
		$path = $paths['old'];
		$new = $paths['new'];
		$categories = $this->categoryRepository->findByPath($path);
		$self = $this->categoryRepository->findOneByPath($path);
		$found = TRUE;
		if (!$self instanceof Category AND count($categories) === 0) {
			$this->addWarningMessage('no categories found');
			$found = FALSE;
		}
		foreach ($categories AS $category) {
			$category->replacePath($path, $new);
			$this->updateCategory($category);
		}
		if ($self instanceof Category) {
			$self->setPath($new);
			$this->updateCategory($self);
		}
		$format = $this->request->getFormat();
		if ($format === 'html') {
			$this->redirect('show', 'CategoryPath', NULL, array('path' => $new));
		} else {
			$this->view->assign('path', $new);
			$this->response->setStatus(200);
		}
	}

}

?>
