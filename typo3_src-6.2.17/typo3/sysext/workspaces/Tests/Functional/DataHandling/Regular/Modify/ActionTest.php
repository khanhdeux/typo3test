<?php
namespace TYPO3\CMS\Workspaces\Tests\Functional\DataHandling\Regular\Modify;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

require_once dirname(dirname(__FILE__)) . '/AbstractActionTestCase.php';

/**
 * Functional test for the DataHandler
 */
class ActionTest extends \TYPO3\CMS\Workspaces\Tests\Functional\DataHandling\Regular\AbstractActionTestCase {

	/**
	 * @var string
	 */
	protected $assertionDataSetDirectory = 'typo3/sysext/workspaces/Tests/Functional/DataHandling/Regular/Modify/DataSet/';

	/**
	 * Content records
	 */

	/**
	 * @test
	 * @see DataSet/Assertion/createContentRecords.csv
	 */
	public function createContents() {
		parent::createContents();
		$this->assertAssertionDataSet('createContents');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Testing #1', 'Testing #2'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/createContentRecordAndDiscardCreatedContentRecord.csv
	 */
	public function createContentAndDiscardCreatedContent() {
		parent::createContentAndDiscardCreatedContent();
		$this->assertAssertionDataSet('createContentNDiscardCreatedContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/createAndCopyContentRecordAndDiscardCopiedContentRecord.csv
	 */
	public function createAndCopyContentAndDiscardCopiedContent() {
		parent::createAndCopyContentAndDiscardCopiedContent();
		$this->assertAssertionDataSet('createNCopyContentNDiscardCopiedContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Testing #1'));
		$this->assertThat($responseSections, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Testing #1 (copy 1)'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/modifyContentRecord.csv
	 */
	public function modifyContent() {
		parent::modifyContent();
		$this->assertAssertionDataSet('modifyContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/deleteContentRecord.csv
	 */
	public function deleteContent() {
		parent::deleteContent();
		$this->assertAssertionDataSet('deleteContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
		$this->assertThat($responseSections, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/deleteLocalizedContentNDeleteContent.csv
	 */
	public function deleteLocalizedContentAndDeleteContent() {
		parent::deleteLocalizedContentAndDeleteContent();
		$this->assertAssertionDataSet('deleteLocalizedContentNDeleteContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, self::VALUE_LanguageId, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #3', '[Translate to Dansk:] Regular Element #3'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/copyContentRecord.csv
	 */
	public function copyContent() {
		parent::copyContent();
		$this->assertAssertionDataSet('copyContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2 (copy 1)'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/localizeContentRecord.csv
	 */
	public function localizeContent() {
		parent::localizeContent();
		$this->assertAssertionDataSet('localizeContent');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, self::VALUE_LanguageId, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', '[Translate to Dansk:] Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/changeContentRecordSorting.csv
	 */
	public function changeContentSorting() {
		parent::changeContentSorting();
		$this->assertAssertionDataSet('changeContentSorting');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/changeContentSortingNDeleteMovedRecord.csv
	 */
	public function changeContentSortingAndDeleteMovedRecord() {
		parent::changeContentSortingAndDeleteMovedRecord();
		$this->assertAssertionDataSet('changeContentSortingNDeleteMovedRecord');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/changeContentSortingNDeleteLiveRecord.csv
	 */
	public function changeContentSortingAndDeleteLiveRecord() {
		parent::changeContentSortingAndDeleteLiveRecord();
		$this->assertAssertionDataSet('changeContentSortingNDeleteLiveRecord');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$this->assertThat($responseSections, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/moveContentRecordToDifferentPage.csv
	 */
	public function moveContentToDifferentPage() {
		parent::moveContentToDifferentPage();
		$this->assertAssertionDataSet('moveContentToDifferentPage');

		$responseSectionsSource = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsSource, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
		$responseSectionsTarget = $this->getFrontendResponse(self::VALUE_PageIdTarget, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsTarget, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/moveContentRecordToDifferentPageAndChangeSorting.csv
	 */
	public function moveContentToDifferentPageAndChangeSorting() {
		parent::moveContentToDifferentPageAndChangeSorting();
		$this->assertAssertionDataSet('moveContentToDifferentPageNChangeSorting');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageIdTarget, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
	}

	/**
	 * Page records
	 */

	/**
	 * @test
	 * @see DataSet/Assertion/createPageRecord.csv
	 */
	public function createPage() {
		parent::createPage();
		$this->assertAssertionDataSet('createPage');

		$responseSections = $this->getFrontendResponse($this->recordIds['newPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/modifyPageRecord.csv
	 */
	public function modifyPage() {
		parent::modifyPage();
		$this->assertAssertionDataSet('modifyPage');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/deletePageRecord.csv
	 */
	public function deletePage() {
		parent::deletePage();
		$this->assertAssertionDataSet('deletePage');

		$response = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId, FALSE);
		$this->assertContains('RuntimeException', $response->getError());
	}

	/**
	 * @test
	 * @see DataSet/Assertion/copyPageRecord.csv
	 */
	public function copyPage() {
		parent::copyPage();
		$this->assertAssertionDataSet('copyPage');

		$responseSections = $this->getFrontendResponse($this->recordIds['newPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Relations'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/localizePageRecord.csv
	 */
	public function localizePage() {
		parent::localizePage();
		$this->assertAssertionDataSet('localizePage');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, self::VALUE_LanguageId, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('[Translate to Dansk:] Relations'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/changePageRecordSorting.csv
	 */
	public function changePageSorting() {
		parent::changePageSorting();
		$this->assertAssertionDataSet('changePageSorting');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Relations'));
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/movePageRecordToDifferentPage.csv
	 */
	public function movePageToDifferentPage() {
		parent::movePageToDifferentPage();
		$this->assertAssertionDataSet('movePageToDifferentPage');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Relations'));
		$this->assertThat($responseSections, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/movePageRecordToDifferentPageAndChangeSorting.csv
	 */
	public function movePageToDifferentPageAndChangeSorting() {
		parent::movePageToDifferentPageAndChangeSorting();
		$this->assertAssertionDataSet('movePageToDifferentPageNChangeSorting');

		$responseSectionsPage = $this->getFrontendResponse(self::VALUE_PageId, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsPage, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Page)->setField('title')->setValues('Relations'));
		$this->assertThat($responseSectionsPage, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1', 'Regular Element #2'));
		$responseSectionsWebsite = $this->getFrontendResponse(self::VALUE_PageIdWebsite, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsWebsite, $this->getRequestSectionStructureHasRecordConstraint()
			->setRecordIdentifier(self::TABLE_Page . ':' . self::VALUE_PageIdWebsite)->setRecordField('__pages')
			->setTable(self::TABLE_Page)->setField('title')->setValues('Target', 'Relations', 'DataHandlerTest'));
	}

	/**
	 * @test
	 * @see DataSet/Assertion/movePageRecordToDifferentPageAndCreatePageRecordAfterMovedPageRecord.csv
	 * @see http://forge.typo3.org/issues/33104
	 * @see http://forge.typo3.org/issues/55573
	 */
	public function movePageToDifferentPageAndCreatePageAfterMovedPage() {
		parent::movePageToDifferentPageAndCreatePageAfterMovedPage();
		$this->assertAssertionDataSet('movePageToDifferentPageNCreatePageAfterMovedPage');

		$responseSections = $this->getFrontendResponse(self::VALUE_PageIdWebsite, 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSections, $this->getRequestSectionStructureHasRecordConstraint()
			->setRecordIdentifier(self::TABLE_Page . ':' . self::VALUE_PageIdWebsite)->setRecordField('__pages')
			->setTable(self::TABLE_Page)->setField('title')->setValues('Target', 'Testing #1', 'DataHandlerTest'));
	}

	/**
	 * @test
	 * @see DataSet/createContentAndCopyDraftPage.csv
	 */
	public function createContentAndCopyDraftPage() {
		parent::createContentAndCopyDraftPage();
		$this->assertAssertionDataSet('createContentAndCopyDraftPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Content)->setField('header')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(static::TABLE_Content)->setField('header')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/createContentAndCopyLivePage.csv
	 */
	public function createContentAndCopyLivePage() {
		parent::createContentAndCopyLivePage();
		$this->assertAssertionDataSet('createContentAndCopyLivePage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Content)->setField('header')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Content)->setField('header')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/createPageAndCopyDraftParentPage.csv
	 */
	public function createPageAndCopyDraftParentPage() {
		parent::createPageAndCopyDraftParentPage();
		$this->assertAssertionDataSet('createPageAndCopyDraftParentPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/createPageAndCopyParentPage.csv
	 */
	public function createPageAndCopyLiveParentPage() {
		parent::createPageAndCopyLiveParentPage();
		$this->assertAssertionDataSet('createPageAndCopyLiveParentPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, self::VALUE_BackendUserId, self::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/createNestedPagesAndCopyDraftParentPage.csv
	 */
	public function createNestedPagesAndCopyDraftParentPage() {
		parent::createNestedPagesAndCopyDraftParentPage();
		$this->assertAssertionDataSet('createNestedPagesAndCopyDraftParentPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/createNestedPagesAndCopyParentPage.csv
	 */
	public function createNestedPagesAndCopyLiveParentPage() {
		parent::createNestedPagesAndCopyLiveParentPage();
		$this->assertAssertionDataSet('createNestedPagesAndCopyLiveParentPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(static::TABLE_Page)->setField('title')->setValues('Testing #1'));
	}

	/**
	 * @test
	 * @see DataSet/deleteContentAndCopyDraftPage.csv
	 */
	public function deleteContentAndCopyDraftPage() {
		parent::deleteContentAndCopyDraftPage();
		$this->assertAssertionDataSet('deleteContentAndCopyDraftPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/deleteContentAndCopyLivePage.csv
	 */
	public function deleteContentAndCopyLivePage() {
		parent::deleteContentAndCopyLivePage();
		$this->assertAssertionDataSet('deleteContentAndCopyLivePage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
	}

	/**
	 * @test
	 * @see DataSet/changeContentSortingAndCopyDraftPage.csv
	 */
	public function changeContentSortingAndCopyDraftPage() {
		parent::changeContentSortingAndCopyDraftPage();
		$this->assertAssertionDataSet('changeContentSortingAndCopyDraftPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
	}

	/**
	 * @test
	 * @see DataSet/changeContentSortingAndCopyLivePage.csv
	 */
	public function changeContentSortingAndCopyLivePage() {
		parent::changeContentSortingAndCopyLivePage();
		$this->assertAssertionDataSet('changeContentSortingAndCopyLivePage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #1'));
	}

	/**
	 * @test
	 * @see DataSet/moveContentAndCopyDraftPage.csv
	 */
	public function moveContentAndCopyDraftPage() {
		parent::moveContentAndCopyDraftPage();
		$this->assertAssertionDataSet('moveContentAndCopyDraftPage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #0', 'Regular Element #2'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #0'));
	}

	/**
	 * @test
	 * @see DataSet/moveContentAndCopyLivePage.csv
	 */
	public function moveContentAndCopyLivePage() {
		parent::moveContentAndCopyLivePage();
		$this->assertAssertionDataSet('moveContentAndCopyLivePage');

		$responseSectionsLive = $this->getFrontendResponse($this->recordIds['copiedPageId'])->getResponseSections();
		$this->assertThat($responseSectionsLive, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$this->assertThat($responseSectionsLive, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #0'));
		$responseSectionsDraft = $this->getFrontendResponse($this->recordIds['copiedPageId'], 0, static::VALUE_BackendUserId, static::VALUE_WorkspaceId)->getResponseSections();
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionHasRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #2'));
		$this->assertThat($responseSectionsDraft, $this->getRequestSectionDoesNotHaveRecordConstraint()
			->setTable(self::TABLE_Content)->setField('header')->setValues('Regular Element #0'));
	}

	/**
	 * @test
	 * @see DataSet/createPlaceholdersAndDeleteDraftParentPage.csv
	 */
	public function createPlaceholdersAndDeleteDraftParentPage() {
		parent::createPlaceholdersAndDeleteDraftParentPage();
		$this->assertAssertionDataSet('createPlaceholdersAndDeleteDraftParentPage');
	}

	/**
	 * @test
	 * @see DataSet/createPlaceholdersAndDeleteLiveParentPage.csv
	 */
	public function createPlaceholdersAndDeleteLiveParentPage() {
		parent::createPlaceholdersAndDeleteLiveParentPage();
		$this->assertAssertionDataSet('createPlaceholdersAndDeleteLiveParentPage');
	}

}
