<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;

class CategoryController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'category';

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('categories', $this->categoryRepository->findAll());
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function showAction(Category $category) {
		$documents = $this->documentRepository->findByCategory($category);
		$this->view->assign('documents', $documents);
		$this->view->assign('category', $category);
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function createAction(Category $category) {
		$this->categoryRepository->add($category);
		$this->addFlashMessage('Created a new category.');
		$this->redirect('index', NULL, NULL, array('category' => $category));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function updateAction(Category $category) {
		$this->categoryRepository->update($category);
		$this->addFlashMessage('Updated the category.');
		$this->redirect('index', NULL, NULL, array('category' => $category));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function deleteAction(Category $category) {
	#	$documents = $this->documentRepository->findByCategory($category);
	#	if (count($documents) > 0) {
	#		$this->addFlashMessage('cannot delete category because in used by documents.');
	#		$this->redirect('index', NULL, NULL, array('category' => $category));
	#	} else {
			$this->categoryRepository->remove($category);
			$this->addFlashMessage('Deleted a category.');
			$this->redirect('index');
	#	}
	}

}
