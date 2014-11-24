<?php
namespace AchimFritz\Documents\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * Testcase for FileSystemDocument
 */
class DocumentTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function hasAllCategoriesReturnsTrue() {	
		$document = new Document();
		$category = new Category();
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		$categories->add($category);
		$otherCategory = new Category();
		$categories->add($otherCategory);
		$document->addCategory($category);
		$document->addCategory($otherCategory);
		$this->assertSame(TRUE, $document->hasAllCategories($categories));
	}

	/**
	 * @test
	 */
	public function hasAllCategoriesReturnsFalse() {	
		$document = new Document();
		$category = new Category();
		$categories = new \Doctrine\Common\Collections\ArrayCollection();
		$categories->add($category);
		$otherCategory = new Category();
		$categories->add($otherCategory);
		$document->addCategory($category);
		$this->assertSame(FALSE, $document->hasAllCategories($categories));
	}

}
?>
