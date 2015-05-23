<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use AchimFritz\Documents\Domain\Model\Category;

class CategoryController extends \AchimFritz\Rest\Controller\RestController {

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

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
		try {
			$this->documentPersistenceManager->persistAll();
			$this->addFlashMessage('Updated the category.');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index', NULL, NULL, array('category' => $category));
	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\Category $category
	 * @return void
	 */
	public function deleteAction(Category $category) {
		try {
			$this->categoryRepository->remove($category);
			$this->addFlashMessage('Deleted a category.');
		} catch (\AchimFritz\Documents\Domain\Repository\Exception $e) {
			$this->handleException($e);
		}
		$this->redirect('index');
	}

}
