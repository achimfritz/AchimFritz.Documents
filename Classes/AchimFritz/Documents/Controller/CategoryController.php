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
	 * @return void
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
	 * @return void
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
		$category = $this->createCategory($category);
		$this->redirect('show', NULL, NULL, array('category' => $category));
	}

	/**
	 * Updates the given category object
	 *
	 * @param \AchimFritz\Documents\Domain\Model\Category $category The category to update
	 * @return void
	 */
	public function updateAction(Category $category) {
		$category = $this->updateCategory($category);
		$this->redirect('show', NULL, NULL, array('category' => $category));
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
