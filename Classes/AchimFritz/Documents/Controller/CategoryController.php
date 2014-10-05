<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \AchimFritz\Documents\Domain\Model\Category;

/**
 * Category controller for the AchimFritz.Documents package 
 *
 * @Flow\Scope("singleton")
 */
class CategoryController extends AbstractCategoryController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'category';

	/**
	 * Allow creation of resources in createAction()                                                                                              *                                                                                                                                            * @return void
	 */
	public function initializeCreateAction() {
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->forProperty('documents');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documents');
		$sub->allowAllProperties();
	}

	/**
	 * Allow creation of resources in updateAction()                                                                                              *                                                                                                                                            * @return void
	 */
	public function initializeUpdateAction() {
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->forProperty('documents');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documents');
		$sub->allowAllProperties();
	}

	/**
	 * Shows a list of categories
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('categories', $this->categoryRepository->findAll());
	}

	/**
	 * Shows a single category object
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $category The category to show
	 * @return void
	 */
	public function showAction(Category $category) {
		$this->view->assign('category', $category);
	}

	/**
	 * Adds the given new category object to the category repository
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $newCategory A new category to add
	 * @return void
	 */
	public function createAction(Category $category) {
		$format = $this->request->getFormat();
		// we cannot create if category with same path already exists -> merge
		$category = $this->createCategory($category);
		if ($format === 'html') {
			$this->redirect('show', NULL, NULL, array('category' => $category));
		} else {
			$this->view->assign('category', $category);
			$this->response->setStatus(201);
		}
	}

	/**
	 * Updates the given category object
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $category The category to update
	 * @return void
	 */
	public function updateAction(Category $category) {
		$format = $this->request->getFormat();
		// we cannot update if category with same path already exists -> merge and delete
		$category = $this->updateCategory($category);
		if ($format === 'html') {
			$this->redirect('show', NULL, NULL, array('category' => $category));
		} else {
			$this->view->assign('category', $category);
			$this->response->setStatus(200);
		}
	}

	/**
	 * Removes the given category object from the category repository
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $category The category to delete
	 * @return void
	 */
	public function deleteAction(Category $category) {
		$this->deleteCategory($category);
		$this->redirect('list');
	}

}

?>
