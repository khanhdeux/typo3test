<?php
namespace TYPO3\CMS\Filelist;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FolderInterface;

/**
 * Class for rendering of File>Filelist
 *
 * @author Kasper Skårhøj <kasperYYYY@typo3.com>
 */
class FileList extends \TYPO3\CMS\Backend\RecordList\AbstractRecordList {

	/**
	 * Default Max items shown
	 *
	 * @todo Define visibility
	 */
	public $iLimit = 40;

	/**
	 * Boolean. Thumbnails on records containing files (pictures)
	 *
	 * @todo Define visibility
	 */
	public $thumbs = 0;

	/**
	 * @todo Define visibility
	 */
	public $widthGif = '<img src="clear.gif" width="1" height="1" hspace="165" alt="" />';

	/**
	 * Max length of strings
	 *
	 * @todo Define visibility
	 */
	public $fixedL = 30;

	/**
	 * @todo Define visibility
	 */
	public $script = '';

	/**
	 * If TRUE click menus are generated on files and folders
	 *
	 * @todo Define visibility
	 */
	public $clickMenus = 1;

	/**
	 * The field to sort by
	 *
	 * @todo Define visibility
	 */
	public $sort = '';

	/**
	 * Reverse sorting flag
	 *
	 * @todo Define visibility
	 */
	public $sortRev = 1;

	/**
	 * @todo Define visibility
	 */
	public $firstElementNumber = 0;

	/**
	 * @todo Define visibility
	 */
	public $clipBoard = 0;

	/**
	 * @todo Define visibility
	 */
	public $bigControlPanel = 0;

	/**
	 * @todo Define visibility
	 */
	public $JScode = '';

	/**
	 * @todo Define visibility
	 */
	public $HTMLcode = '';

	/**
	 * @todo Define visibility
	 */
	public $totalbytes = 0;

	/**
	 * @todo Define visibility
	 */
	public $dirs = array();

	/**
	 * @todo Define visibility
	 */
	public $files = array();

	/**
	 * @todo Define visibility
	 */
	public $path = '';

	/**
	 * @var \TYPO3\CMS\Core\Resource\Folder
	 */
	protected $folderObject;

	/**
	 * Counting the elements no matter what
	 *
	 * @todo Define visibility
	 */
	public $eCounter = 0;

	/**
	 * @todo Define visibility
	 */
	public $dirCounter = 0;

	/**
	 * @todo Define visibility
	 */
	public $totalItems = '';

	/**
	 * @todo Define visibility
	 */
	public $CBnames = array();

	/**
	 * @var \TYPO3\CMS\Backend\Clipboard\Clipboard $clipObj
	 */
	public $clipObj;

	/**
	 * @var ResourceFactory
	 */
	protected $resourceFactory;

	/**
	 * @param ResourceFactory $resourceFactory
	 */
	public function injectResourceFactory(ResourceFactory $resourceFactory) {
		$this->resourceFactory = $resourceFactory;
	}

	/**
	 * Initialization of class
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder $folderObject The folder to work on
	 * @param integer $pointer Pointer
	 * @param boolean $sort Sorting column
	 * @param boolean $sortRev Sorting direction
	 * @param boolean $bigControlPanel Show clipboard flag
	 * @return void
	 * @todo Define visibility
	 */
	public function start(\TYPO3\CMS\Core\Resource\Folder $folderObject, $pointer, $sort, $sortRev, $clipBoard = FALSE, $bigControlPanel = FALSE) {
		$this->script = BackendUtility::getModuleUrl('file_list');
		$this->folderObject = $folderObject;
		$this->counter = 0;
		$this->totalbytes = 0;
		$this->JScode = '';
		$this->HTMLcode = '';
		$this->path = $folderObject->getReadablePath();
		$this->sort = $sort;
		$this->sortRev = $sortRev;
		$this->firstElementNumber = $pointer;
		$this->clipBoard = $clipBoard;
		$this->bigControlPanel = $bigControlPanel;
		// Setting the maximum length of the filenames to the user's settings or minimum 30 (= $this->fixedL)
		$this->fixedL = max($this->fixedL, $GLOBALS['BE_USER']->uc['titleLen']);
		$GLOBALS['LANG']->includeLLFile('EXT:lang/locallang_common.xlf');
		$this->resourceFactory = ResourceFactory::getInstance();
	}

	/**
	 * Reading files and directories, counting elements and generating the list in ->HTMLcode
	 *
	 * @return void
	 * @todo Define visibility
	 */
	public function generateList() {
		$this->HTMLcode .= $this->getTable('fileext,tstamp,size,rw,_REF_');
	}

	/**
	 * Return the buttons used by the file list to include in the top header
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder $folderObject
	 * @return array
	 */
	public function getButtonsAndOtherMarkers(\TYPO3\CMS\Core\Resource\Folder $folderObject) {
		$otherMarkers = array(
			'PAGE_ICON' => '',
			'TITLE' => ''
		);
		$buttons = array(
			'level_up' => $this->getLinkToParentFolder($folderObject),
			'refresh' => '',
			'title' => '',
			'page_icon' => '',
			'PASTE' => ''
		);
		// Makes the code for the folder icon in the top
		if ($folderObject) {
			$title = htmlspecialchars($folderObject->getReadablePath());
			// Start compiling the HTML
			// If this is some subFolder under the mount root....
			if ($folderObject->getStorage()->isWithinFileMountBoundaries($folderObject)) {
				// The icon with link
				$otherMarkers['PAGE_ICON'] = IconUtility::getSpriteIconForResource($folderObject, array('title' => $title));
				// No HTML specialchars here - HTML like <strong> </strong> is allowed
				$otherMarkers['TITLE'] .= GeneralUtility::removeXSS(GeneralUtility::fixed_lgd_cs($title, -($this->fixedL + 20)));
			} else {
				// This is the root folder
				$otherMarkers['PAGE_ICON'] = IconUtility::getSpriteIconForResource($folderObject, array('title' => $title, 'mount-root' => TRUE));
				$otherMarkers['TITLE'] .= htmlspecialchars(GeneralUtility::fixed_lgd_cs($title, -($this->fixedL + 20)));
			}
			if ($this->clickMenus) {
				$otherMarkers['PAGE_ICON'] = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($otherMarkers['PAGE_ICON'], $folderObject->getCombinedIdentifier());
			}
			// Add paste button if clipboard is initialized
			if ($this->clipObj instanceof \TYPO3\CMS\Backend\Clipboard\Clipboard && $folderObject->checkActionPermission('write')) {
				$elFromTable = $this->clipObj->elFromTable('_FILE');
				if (count($elFromTable)) {
					$addPasteButton = TRUE;
					foreach ($elFromTable as $element) {
						$clipBoardElement = $this->resourceFactory->retrieveFileOrFolderObject($element);
						if ($clipBoardElement instanceof Folder && $clipBoardElement->getStorage()->isWithinFolder($clipBoardElement, $folderObject)) {
							$addPasteButton = FALSE;
						}
					}
					if ($addPasteButton) {
						$buttons['PASTE'] = '<a href="' . htmlspecialchars($this->clipObj->pasteUrl('_FILE', $folderObject->getCombinedIdentifier())) . '" onclick="return ' . htmlspecialchars($this->clipObj->confirmMsg('_FILE', $this->path, 'into', $elFromTable)) . '" title="' . $GLOBALS['LANG']->getLL('clip_paste', TRUE) . '">' . IconUtility::getSpriteIcon('actions-document-paste-after') . '</a>';
					}
				}
			}

		}
		$buttons['refresh'] = '<a href="' . htmlspecialchars($this->listURL()) . '" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:labels.reload', TRUE) . '">' . IconUtility::getSpriteIcon('actions-system-refresh') . '</a>';
		return array($buttons, $otherMarkers);
	}

	/**
	 * Wrapping input string in a link with clipboard command.
	 *
	 * @param string $string String to be linked - must be htmlspecialchar'ed / prepared before.
	 * @param string $table table - NOT USED
	 * @param string $cmd "cmd" value
	 * @param string $warning Warning for JS confirm message
	 * @return string Linked string
	 * @todo Define visibility
	 */
	public function linkClipboardHeaderIcon($string, $table, $cmd, $warning = '') {
		$onClickEvent = 'document.dblistForm.cmd.value=\'' . $cmd . '\';document.dblistForm.submit();';
		if ($warning) {
			$onClickEvent = 'if (confirm(' . GeneralUtility::quoteJSvalue($warning) . ')){' . $onClickEvent . '}';
		}
		return '<a href="#" onclick="' . htmlspecialchars($onClickEvent) . 'return false;">' . $string . '</a>';
	}

	/**
	 * Returns a table with directories and files listed.
	 *
	 * @param array $rowlist Array of files from path
	 * @return string HTML-table
	 * @todo Define visibility
	 */
	public function getTable($rowlist) {
		// TODO use folder methods directly when they support filters
		$storage = $this->folderObject->getStorage();
		$storage->resetFileAndFolderNameFiltersToDefault();

		// Only render the contents of a browsable storage

		if ($this->folderObject->getStorage()->isBrowsable()) {
			$folders = $storage->getFolderIdentifiersInFolder($this->folderObject->getIdentifier());
			$files = $this->folderObject->getFiles();
			$this->sort = trim($this->sort);
			if ($this->sort !== '') {
				$filesToSort = array();
				/** @var $fileObject \TYPO3\CMS\Core\Resource\File */
				foreach ($files as $fileObject) {
					switch ($this->sort) {
						case 'size':
							$sortingKey = $fileObject->getSize();
							break;
						case 'rw':
							$sortingKey = ($fileObject->checkActionPermission('read') ? 'R' : '' . $fileObject->checkActionPermission('write')) ? 'W' : '';
							break;
						case 'fileext':
							$sortingKey = $fileObject->getExtension();
							break;
						case 'tstamp':
							$sortingKey = $fileObject->getModificationTime();
							break;
						case 'file':
							$sortingKey = $fileObject->getName();
							break;
						default:
							if ($fileObject->hasProperty($this->sort)) {
								$sortingKey = $fileObject->getProperty($this->sort);
							} else {
								$sortingKey = $fileObject->getName();
							}
					}
					$i = 1000000;
					while (isset($filesToSort[$sortingKey . $i])) {
						$i++;
					}
					$filesToSort[$sortingKey . $i] = $fileObject;
				}
				uksort($filesToSort, 'strnatcasecmp');
				if ((int)$this->sortRev === 1) {
					$filesToSort = array_reverse($filesToSort);
				}
				$files = $filesToSort;
			}
			$this->totalItems = count($folders) + count($files);
			// Adds the code of files/dirs
			$out = '';
			$titleCol = 'file';
			// Cleaning rowlist for duplicates and place the $titleCol as the first column always!
			$rowlist = GeneralUtility::rmFromList($titleCol, $rowlist);
			$rowlist = GeneralUtility::uniqueList($rowlist);
			$rowlist = $rowlist ? $titleCol . ',' . $rowlist : $titleCol;
			if ($this->bigControlPanel || $this->clipBoard) {
				$rowlist = str_replace('file,', 'file,_CLIPBOARD_,', $rowlist);
			}
			$this->fieldArray = explode(',', $rowlist);
			$folderObjects = array();
			foreach ($folders as $folder) {
				$folderObjects[] = $storage->getFolder($folder, TRUE);
			}

			$folderObjects = \TYPO3\CMS\Core\Resource\Utility\ListUtility::resolveSpecialFolderNames($folderObjects);
			uksort($folderObjects, 'strnatcasecmp');

			// Directories are added
			$iOut = $this->formatDirList($folderObjects);
			// Files are added
			$iOut .= $this->formatFileList($files, $titleCol);
			// Header line is drawn
			$theData = array();
			foreach ($this->fieldArray as $v) {
				if ($v == '_CLIPBOARD_' && $this->clipBoard) {
					$cells = array();
					$table = '_FILE';
					$elFromTable = $this->clipObj->elFromTable($table);
					if (count($elFromTable) && $this->folderObject->checkActionPermission('write')) {
						$addPasteButton = TRUE;
						foreach ($elFromTable as $element) {
							$clipBoardElement = $this->resourceFactory->retrieveFileOrFolderObject($element);
							if ($clipBoardElement instanceof Folder && $clipBoardElement->getStorage()->isWithinFolder($clipBoardElement, $this->folderObject)) {
								$addPasteButton = FALSE;
							}
						}
						if ($addPasteButton) {
							$cells[] = '<a class="btn" href="' . htmlspecialchars($this->clipObj->pasteUrl('_FILE', $this->folderObject->getCombinedIdentifier())) . '" onclick="return ' . htmlspecialchars($this->clipObj->confirmMsg('_FILE', $this->path, 'into', $elFromTable)) . '" title="' . $GLOBALS['LANG']->getLL('clip_paste', 1) . '">' . IconUtility::getSpriteIcon('actions-document-paste-after') . '</a>';
						}
					}
					if ($this->clipObj->current != 'normal' && $iOut) {
						$cells[] = $this->linkClipboardHeaderIcon(IconUtility::getSpriteIcon('actions-edit-copy', array('title' => $GLOBALS['LANG']->getLL('clip_selectMarked', TRUE))), $table, 'setCB');
						$cells[] = $this->linkClipboardHeaderIcon(IconUtility::getSpriteIcon('actions-edit-delete', array('title' => $GLOBALS['LANG']->getLL('clip_deleteMarked'))), $table, 'delete', $GLOBALS['LANG']->getLL('clip_deleteMarkedWarning'));
						$onClick = 'checkOffCB(\'' . implode(',', $this->CBnames) . '\', this); return false;';
						$cells[] = '<a class="cbcCheckAll" rel="" href="#" onclick="' . htmlspecialchars($onClick) . '" title="' . $GLOBALS['LANG']->getLL('clip_markRecords', TRUE) . '">' . IconUtility::getSpriteIcon('actions-document-select') . '</a>';
					}
					$theData[$v] = implode('', $cells);
				} else {
					// Normal row:
					$theT = $this->linkWrapSort($GLOBALS['LANG']->getLL('c_' . $v, TRUE), $this->folderObject->getCombinedIdentifier(), $v);
					$theData[$v] = $theT;
				}
			}

			$out .= '<thead>' . $this->addelement(1, '&nbsp;', $theData) . '</thead>';
			$out .= '<tbody>' . $iOut . '</tbody>';
			// half line is drawn
			// finish
			$out = '
		<!--
			File list table:
		-->
			<table class="t3-table" id="typo3-filelist">
				' . $out . '
			</table>';

		} else {
			/** @var $flashMessage \TYPO3\CMS\Core\Messaging\FlashMessage */
			$flashMessage = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $GLOBALS['LANG']->getLL('storageNotBrowsableMessage'), $GLOBALS['LANG']->getLL('storageNotBrowsableTitle'), \TYPO3\CMS\Core\Messaging\FlashMessage::INFO);
			$out = $flashMessage->render();
		}
		return $out;
	}


	/**
	 * If there is a parent folder and user has access to it, return an icon
	 * which is linked to the filelist of the parent folder.
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder $currentFolder
	 * @return string
	 */
	protected function getLinkToParentFolder(\TYPO3\CMS\Core\Resource\Folder $currentFolder) {
		$levelUp = '';
		try {
			$currentStorage = $currentFolder->getStorage();
			$parentFolder = $currentFolder->getParentFolder();
			if ($parentFolder->getIdentifier() !== $currentFolder->getIdentifier() && $currentStorage->isWithinFileMountBoundaries($parentFolder)) {
				$levelUp = $this->linkWrapDir(
					IconUtility::getSpriteIcon(
						'actions-view-go-up',
						array('title' => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.upOneLevel', TRUE))
					),
					$parentFolder
				);
			}
		} catch (\Exception $e) {}
		return $levelUp;
	}
	/**
	 * Gets the number of files and total size of a folder
	 *
	 * @return string
	 * @todo Define visibility
	 */
	public function getFolderInfo() {
		if ($this->counter == 1) {
			$fileLabel = $GLOBALS['LANG']->getLL('file', TRUE);
		} else {
			$fileLabel = $GLOBALS['LANG']->getLL('files', TRUE);
		}
		return $this->counter . ' ' . $fileLabel . ', ' . GeneralUtility::formatSize($this->totalbytes, $GLOBALS['LANG']->getLL('byteSizeUnits', TRUE));
	}

	/**
	 * This returns tablerows for the directories in the array $items['sorting'].
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder[] $folders Folders of \TYPO3\CMS\Core\Resource\Folder
	 * @return string HTML table rows.
	 * @todo Define visibility
	 */
	public function formatDirList(array $folders) {
		$out = '';
		foreach ($folders as $folderName => $folderObject) {
			$role = $folderObject->getRole();
			if ($role === FolderInterface::ROLE_PROCESSING) {
				// don't show processing-folder
				continue;
			}
			if ($role !== FolderInterface::ROLE_DEFAULT) {
				$displayName = '<strong>' . htmlspecialchars($folderName) . '</strong>';
			} else {
				$displayName = htmlspecialchars($folderName);
			}

			list($flag, $code) = $this->fwd_rwd_nav();
			$out .= $code;
			if ($flag) {
				$isLocked = $folderObject instanceof \TYPO3\CMS\Core\Resource\InaccessibleFolder;
				$isWritable = $folderObject->checkActionPermission('write');

				// Initialization
				$this->counter++;

				// The icon with link
				$theIcon = IconUtility::getSpriteIconForResource($folderObject, array('title' => $folderName));
				if (!$isLocked && $this->clickMenus) {
					$theIcon = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($theIcon, $folderObject->getCombinedIdentifier());
				}

				// Preparing and getting the data-array
				$theData = array();
				if ($isLocked) {
					foreach ($this->fieldArray as $field) {
						$theData[$field] = '';
					}
					$theData['file'] = $displayName;
				} else {
					foreach ($this->fieldArray as $field) {
						switch ($field) {
							case 'size':
								try {
									$numFiles = $folderObject->getFileCount();
								} catch (\TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException $e) {
									$numFiles = 0;
								}
								$theData[$field] = $numFiles . ' ' . $GLOBALS['LANG']->getLL(($numFiles === 1 ? 'file' : 'files'), TRUE);
								break;
							case 'rw':
								$theData[$field] = '<span class="typo3-red"><strong>' . $GLOBALS['LANG']->getLL('read', TRUE) . '</strong></span>' . (!$isWritable ? '' : '<span class="typo3-red"><strong>' . $GLOBALS['LANG']->getLL('write', TRUE) . '</strong></span>');
								break;
							case 'fileext':
								$theData[$field] = $GLOBALS['LANG']->getLL('folder', TRUE);
								break;
							case 'tstamp':
								// @todo: FAL: how to get the mtime info -- $theData[$field] = \TYPO3\CMS\Backend\Utility\BackendUtility::date($theFile['tstamp']);
								$theData[$field] = '-';
								break;
							case 'file':
								$theData[$field] = $this->linkWrapDir($displayName, $folderObject);
								break;
							case '_CLIPBOARD_':
								$temp = '';
								if ($this->bigControlPanel) {
									$temp .= $this->makeEdit($folderObject);
								}
								$temp .= $this->makeClip($folderObject);
								$theData[$field] = $temp;
								break;
							case '_REF_':
								$theData[$field] = $this->makeRef($folderObject);
								break;
							default:
								$theData[$field] = GeneralUtility::fixed_lgd_cs($theFile[$field], $this->fixedL);
						}
					}
				}
				$out .= $this->addelement(1, $theIcon, $theData);
			}
			$this->eCounter++;
			$this->dirCounter = $this->eCounter;
		}
		return $out;
	}

	/**
	 * Wraps the directory-titles
	 *
	 * @param string $title String to be wrapped in links
	 * @param \TYPO3\CMS\Core\Resource\Folder $folderObject Folder to work on
	 * @return string HTML
	 * @todo Define visibility
	 */
	public function linkWrapDir($title, \TYPO3\CMS\Core\Resource\Folder $folderObject) {
		$href = $this->backPath . $this->script . '&id=' . rawurlencode($folderObject->getCombinedIdentifier());
		$onclick = ' onclick="' . htmlspecialchars(('top.document.getElementsByName("navigation")[0].contentWindow.Tree.highlightActiveItem("file","folder' . GeneralUtility::md5int($folderObject->getCombinedIdentifier()) . '_"+top.fsMod.currentBank)')) . '"';
		// Sometimes $code contains plain HTML tags. In such a case the string should not be modified!
		if ((string)$title === strip_tags($title)) {
			return '<a href="' . htmlspecialchars($href) . '"' . $onclick . ' title="' . htmlspecialchars($title) . '">' . GeneralUtility::fixed_lgd_cs($title, $this->fixedL) . '</a>';
		} else {
			return '<a href="' . htmlspecialchars($href) . '"' . $onclick . '>' . $title . '</a>';
		}
	}

	/**
	 * Wraps filenames in links which opens them in a window IF they are in web-path.
	 *
	 * @param string $code String to be wrapped in links
	 * @param \TYPO3\CMS\Core\Resource\File $fileObject File to be linked
	 * @return string HTML
	 * @todo Define visibility
	 */
	public function linkWrapFile($code, \TYPO3\CMS\Core\Resource\File $fileObject) {
		$fileUrl = $fileObject->getPublicUrl(TRUE);
		if ($fileUrl) {
			$aOnClick = 'return top.openUrlInWindow(\'' . $fileUrl . '\', \'WebFile\');';
			$code = '<a href="#" title="' . htmlspecialchars($code) . '" onclick="' . htmlspecialchars($aOnClick) . '">' . GeneralUtility::fixed_lgd_cs($code, $this->fixedL) . '</a>';
		}
		return $code;
	}

	/**
	 * Returns list URL; This is the URL of the current script with id and imagemode parameters, that's all.
	 * The URL however is not relative (with the backpath), otherwise GeneralUtility::sanitizeLocalUrl() would say that
	 * the URL would be invalid
	 *
	 * @return string URL
	 * @todo Define visibility
	 */
	public function listURL() {
		return GeneralUtility::linkThisScript(array(
			'target' => rawurlencode($this->folderObject->getCombinedIdentifier()),
			'imagemode' => $this->thumbs
		));
	}

	/**
	 * Returns some data specific for the directories...
	 *
	 * @param \TYPO3\CMS\Core\Resource\Folder $folderObject File information array
	 * @return array (title, icon, path)
	 * @deprecated since 6.2 - will be removed two versions later without replacement
	 */
	public function dirData(\TYPO3\CMS\Core\Resource\Folder $folderObject) {
		GeneralUtility::logDeprecatedFunction();

		$title = htmlspecialchars($folderObject->getName());
		$icon = 'apps-filetree-folder-default';
		$role = $folderObject->getRole();
		if ($role === FolderInterface::ROLE_TEMPORARY) {
			$title = '<strong>' . $GLOBALS['LANG']->getLL('temp', TRUE) . '</strong>';
			$icon = 'apps-filetree-folder-temp';
		} elseif ($role === FolderInterface::ROLE_RECYCLER) {
			$icon = 'apps-filetree-folder-recycler';
			$title = '<strong>' . $GLOBALS['LANG']->getLL('recycler', TRUE) . '</strong>';
		}
		return array($title, $icon, $folderObject->getIdentifier());
	}

	/**
	 * This returns tablerows for the files in the array $items['sorting'].
	 *
	 * @param \TYPO3\CMS\Core\Resource\File[] $files File items
	 * @return string HTML table rows.
	 * @todo Define visibility
	 */
	public function formatFileList(array $files) {
		$out = '';
		// first two keys are "0" (default) and "-1" (multiple), after that comes the "other languages"
		$allSystemLanguages = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Configuration\\TranslationConfigurationProvider')->getSystemLanguages();
		$systemLanguages = array_filter($allSystemLanguages, function($languageRecord) {
			if ($languageRecord['uid'] === -1 || $languageRecord['uid'] === 0 || !$GLOBALS['BE_USER']->checkLanguageAccess($languageRecord['uid'])) {
				return FALSE;
			} else {
				return TRUE;
			}
		});

		foreach ($files as $fileObject) {
			list($flag, $code) = $this->fwd_rwd_nav();
			$out .= $code;
			if ($flag) {
				// Initialization
				$this->counter++;
				$this->totalbytes += $fileObject->getSize();
				$ext = $fileObject->getExtension();
				$fileName = trim($fileObject->getName());
				// The icon with link
				$theIcon = IconUtility::getSpriteIconForResource($fileObject, array('title' => $fileName . ' [' . (int)$fileObject->getUid() . ']'));
				if ($this->clickMenus) {
					$theIcon = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($theIcon, $fileObject->getCombinedIdentifier());
				}
				// Preparing and getting the data-array
				$theData = array();
				foreach ($this->fieldArray as $field) {
					switch ($field) {
						case 'size':
							$theData[$field] = GeneralUtility::formatSize($fileObject->getSize(), $GLOBALS['LANG']->getLL('byteSizeUnits', TRUE));
							break;
						case 'rw':
							$theData[$field] = '' . (!$fileObject->checkActionPermission('read') ? ' ' : '<span class="typo3-red"><strong>' . $GLOBALS['LANG']->getLL('read', TRUE) . '</strong></span>') . (!$fileObject->checkActionPermission('write') ? '' : '<span class="typo3-red"><strong>' . $GLOBALS['LANG']->getLL('write', TRUE) . '</strong></span>');
							break;
						case 'fileext':
							$theData[$field] = strtoupper($ext);
							break;
						case 'tstamp':
							$theData[$field] = BackendUtility::date($fileObject->getModificationTime());
							break;
						case '_CLIPBOARD_':
							$temp = '';
							if ($this->bigControlPanel) {
								$temp .= $this->makeEdit($fileObject);
							}
							$temp .= $this->makeClip($fileObject);
							if (!empty($systemLanguages)) {
								$temp .= '<a class="filelist-translationToggler" data-fileid="' . $fileObject->getUid() . '">' .
									IconUtility::getSpriteIcon('mimetypes-x-content-page-language-overlay') . '</a>';
							}
							$theData[$field] = $temp;
							break;
						case '_REF_':
							$theData[$field] = $this->makeRef($fileObject);
							break;
						case 'file':
							$theData[$field] = $this->linkWrapFile(htmlspecialchars($fileName), $fileObject);

							if ($fileObject->isMissing()) {
								$flashMessage = \TYPO3\CMS\Core\Resource\Utility\BackendUtility::getFlashMessageForMissingFile($fileObject);
								$theData[$field] .= $flashMessage->render();
							// Thumbnails?
							} elseif ($this->thumbs && $this->isImage($ext)) {
								$processedFile = $fileObject->process(\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGEPREVIEW, array());
								if ($processedFile) {
									$thumbUrl = $processedFile->getPublicUrl(TRUE);
									$theData[$field] .= '<br /><img src="' . $thumbUrl . '" title="' . htmlspecialchars($fileName) . '" alt="" />';
								}
							}

							if (!empty($systemLanguages) && $fileObject->isIndexed() && $fileObject->checkActionPermission('write')) {
								$metaDataRecord = $fileObject->_getMetaData();
								$translations = $this->getTranslationsForMetaData($metaDataRecord);
								$languageCode = '';

								foreach ($systemLanguages as $language) {
									$languageId = $language['uid'];
									$flagIcon = $language['flagIcon'];

									if (array_key_exists($languageId, $translations)) {
										$flagButtonIcon = IconUtility::getSpriteIcon(
											'actions-document-open',
											array('title' => $fileName),
											array($flagIcon . '-overlay' => array()));
										$data = array(
											'sys_file_metadata' => array($translations[$languageId]['uid'] => 'edit')
										);
										$editOnClick = BackendUtility::editOnClick(GeneralUtility::implodeArrayForUrl('edit', $data), $GLOBALS['BACK_PATH'], $this->listUrl());
										$languageCode .= sprintf('<a href="#" onclick="%s">%s</a>', htmlspecialchars($editOnClick), $flagButtonIcon);

									} else {
										$href = $GLOBALS['SOBE']->doc->issueCommand(
											'&cmd[sys_file_metadata][' . $metaDataRecord['uid'] . '][localize]=' . $languageId,
											$this->backPath . 'alt_doc.php?justLocalized=' . rawurlencode(('sys_file_metadata:' . $metaDataRecord['uid'] . ':' . $languageId)) .
											'&returnUrl=' . rawurlencode($this->listURL()) . BackendUtility::getUrlToken('editRecord')
										);
										$flagButtonIcon = IconUtility::getSpriteIcon($flagIcon);
										$languageCode .= sprintf('<a href="%s">%s</a> ', htmlspecialchars($href), $flagButtonIcon);
									}
								}

								// Hide flag button bar when not translated yet
								$theData[$field] .= '<div class="localisationData" data-fileid="' . $fileObject->getUid() . '"' .
										(empty($translations) ? ' style="display: none;"' : '') . '>' . $languageCode . '</div>';
							}

							break;
						default:
							$theData[$field] = '';
							if ($fileObject->hasProperty($field)) {
								$theData[$field] = htmlspecialchars(GeneralUtility::fixed_lgd_cs($fileObject->getProperty($field), $this->fixedL));
							}
					}
				}
				$out .= $this->addelement(1, $theIcon, $theData);

			}
			$this->eCounter++;
		}
		return $out;
	}

	/**
	 * Fetch the translations for a sys_file_metadata record
	 *
	 * @param $metaDataRecord
	 * @return array keys are the sys_language uids, values are the $rows
	 */
	protected function getTranslationsForMetaData($metaDataRecord) {
		$where = $GLOBALS['TCA']['sys_file_metadata']['ctrl']['transOrigPointerField'] . '=' . (int)$metaDataRecord['uid'] .
			' AND ' . $GLOBALS['TCA']['sys_file_metadata']['ctrl']['languageField'] . '>0';
		$translationRecords = $this->getDatabaseConnection()->exec_SELECTgetRows('*', 'sys_file_metadata', $where);
		$translations = array();
		foreach ($translationRecords as $record) {
			$translations[$record[$GLOBALS['TCA']['sys_file_metadata']['ctrl']['languageField']]] = $record;
		}
		return $translations;
	}

	/**
	 * Returns TRUE if $ext is an image-extension according to $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
	 *
	 * @param string $ext File extension
	 * @return boolean
	 * @todo Define visibility
	 */
	public function isImage($ext) {
		return GeneralUtility::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], strtolower($ext));
	}

	/**
	 * Wraps the directory-titles ($code) in a link to filelist/mod1/index.php (id=$path) and sorting commands...
	 *
	 * @param string $code String to be wrapped
	 * @param string $folderIdentifier ID (path)
	 * @param string $col Sorting column
	 * @return string HTML
	 * @todo Define visibility
	 */
	public function linkWrapSort($code, $folderIdentifier, $col) {
		if ($this->sort === $col) {
			// Check reverse sorting
			$params = '&SET[sort]=' . $col . '&SET[reverse]=' . ($this->sortRev ? '0' : '1');
			$sortArrow = IconUtility::getSpriteIcon('status-status-sorting-light-' . ($this->sortRev ? 'desc' : 'asc'));
		} else {
			$params = '&SET[sort]=' . $col . '&SET[reverse]=0';
			$sortArrow = '';
		}
		$href = GeneralUtility::resolveBackPath(($GLOBALS['BACK_PATH'] . $this->script)) . '&id=' . rawurlencode($folderIdentifier) . $params;
		return '<a href="' . htmlspecialchars($href) . '">' . $code . $sortArrow . '</a>';
	}

	/**
	 * Creates the clipboard control pad
	 *
	 * @param \TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\Folder $fileOrFolderObject Array with information about the file/directory for which to make the clipboard panel for the listing.
	 * @return string HTML-table
	 * @todo Define visibility
	 */
	public function makeClip($fileOrFolderObject) {
		if (!$fileOrFolderObject->checkActionPermission('read')) {
			return '';
		}
		$cells = array();
		$fullIdentifier = $fileOrFolderObject->getCombinedIdentifier();
		$md5 = GeneralUtility::shortmd5($fullIdentifier);
		// For normal clipboard, add copy/cut buttons:
		if ($this->clipObj->current == 'normal') {
			$isSel = $this->clipObj->isSelected('_FILE', $md5);
			$cells[] = '<a href="' . htmlspecialchars($this->clipObj->selUrlFile($fullIdentifier, 1, ($isSel == 'copy'))) . '">' . IconUtility::getSpriteIcon(('actions-edit-copy' . ($isSel == 'copy' ? '-release' : '')), array('title' => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.copy', TRUE))) . '</a>';
			// we can only cut if file can be moved
			if ($fileOrFolderObject->checkActionPermission('move')) {
				$cells[] = '<a href="' . htmlspecialchars($this->clipObj->selUrlFile($fullIdentifier, 0, ($isSel == 'cut'))) . '">' . IconUtility::getSpriteIcon(('actions-edit-cut' . ($isSel == 'cut' ? '-release' : '')), array('title' => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.cut', TRUE))) . '</a>';
			} else {
				$cells[] = IconUtility::getSpriteIcon('empty-empty');
			}
		} else {
			// For numeric pads, add select checkboxes:
			$n = '_FILE|' . $md5;
			$this->CBnames[] = $n;
			$checked = $this->clipObj->isSelected('_FILE', $md5) ? ' checked="checked"' : '';
			$cells[] = '<input type="hidden" name="CBH[' . $n . ']" value="0" />' . '<input type="checkbox" name="CBC[' . $n . ']" value="' . htmlspecialchars($fullIdentifier) . '" class="smallCheckboxes"' . $checked . ' />';
		}
		// Display PASTE button, if directory:
		$elFromTable = $this->clipObj->elFromTable('_FILE');
		if (is_a($fileOrFolderObject, 'TYPO3\\CMS\\Core\\Resource\\Folder') && count($elFromTable) && $fileOrFolderObject->checkActionPermission('write')) {
			$addPasteButton = TRUE;
			foreach ($elFromTable as $element) {
				$clipBoardElement = $this->resourceFactory->retrieveFileOrFolderObject($element);
				if ($clipBoardElement instanceof Folder && $clipBoardElement->getStorage()->isWithinFolder($clipBoardElement, $fileOrFolderObject)) {
					$addPasteButton = FALSE;
				}
			}
			if ($addPasteButton) {
				$cells[] = '<a class="btn" href="' . htmlspecialchars($this->clipObj->pasteUrl('_FILE', $fullIdentifier)) . '" onclick="return ' . htmlspecialchars($this->clipObj->confirmMsg('_FILE', $fullIdentifier, 'into', $elFromTable)) . '" title="' . $GLOBALS['LANG']->getLL('clip_pasteInto', TRUE) . '">' . IconUtility::getSpriteIcon('actions-document-paste-into') . '</a>';
			}
		}
		// Compile items into a DIV-element:
		return '							<!-- CLIPBOARD PANEL: -->
											<div class="typo3-clipCtrl">
												' . implode('
												', $cells) . '
											</div>';
	}

	/**
	 * Creates the edit control section
	 *
	 * @param \TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\Folder $fileOrFolderObject Array with information about the file/directory for which to make the edit control section for the listing.
	 * @return string HTML-table
	 * @todo Define visibility
	 */
	public function makeEdit($fileOrFolderObject) {
		$cells = array();
		$fullIdentifier = $fileOrFolderObject->getCombinedIdentifier();
		// Edit metadata of file
		try {
			if (is_a($fileOrFolderObject, 'TYPO3\\CMS\\Core\\Resource\\File') && $fileOrFolderObject->isIndexed() && $fileOrFolderObject->checkActionPermission('write')) {
				$metaData = $fileOrFolderObject->_getMetaData();
				$data = array(
					'sys_file_metadata' => array($metaData['uid'] => 'edit')
				);
				$editOnClick = BackendUtility::editOnClick(GeneralUtility::implodeArrayForUrl('edit', $data), $GLOBALS['BACK_PATH'], $this->listUrl());
				$title = htmlspecialchars($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.editMetadata'));
				$cells['editmetadata'] = '<a href="#" onclick="' . $editOnClick . '" title="' . $title . '">'
					. IconUtility::getSpriteIcon('actions-document-open') . '</a>';
			} else {
				$cells['editmetadata'] = IconUtility::getSpriteIcon('empty-empty');
			}
		} catch (\Exception $e) {
			$cells['editmetadata'] = IconUtility::getSpriteIcon('empty-empty');
		}
		// Edit file content (if editable)
		if (is_a($fileOrFolderObject, 'TYPO3\\CMS\\Core\\Resource\\File') && $fileOrFolderObject->checkActionPermission('write') && GeneralUtility::inList($GLOBALS['TYPO3_CONF_VARS']['SYS']['textfile_ext'], $fileOrFolderObject->getExtension())) {
			$editOnClick = 'top.content.list_frame.location.href=top.TS.PATH_typo3+\'file_edit.php?target=' . rawurlencode($fullIdentifier) . '&returnUrl=\'+top.rawurlencode(top.content.list_frame.document.location.pathname+top.content.list_frame.document.location.search);return false;';
			$cells['edit'] = '<a href="#" onclick="' . $editOnClick . '" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.editcontent') . '">' . IconUtility::getSpriteIcon('actions-page-open') . '</a>';
		} else {
			$cells['edit'] = IconUtility::getSpriteIcon('empty-empty');
		}
		// rename the file
		if ($fileOrFolderObject->checkActionPermission('rename')) {
			$renameOnClick = 'top.content.list_frame.location.href = top.TS.PATH_typo3+\'file_rename.php?target=' . rawurlencode($fullIdentifier) . '&returnUrl=\'+top.rawurlencode(top.content.list_frame.document.location.pathname+top.content.list_frame.document.location.search);return false;';
			$cells['rename'] = '<a href="#" onclick="' . $renameOnClick . '"  title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.rename') . '">' . IconUtility::getSpriteIcon('actions-edit-rename') . '</a>';
		} else {
			$cells['rename'] = IconUtility::getSpriteIcon('empty-empty');
		}
		if ($fileOrFolderObject->checkActionPermission('read')) {
			if (is_a($fileOrFolderObject, 'TYPO3\\CMS\\Core\\Resource\\Folder')) {
				$infoOnClick = 'top.launchView( \'_FOLDER\', \'' . $fullIdentifier . '\');return false;';
			} elseif (is_a($fileOrFolderObject, 'TYPO3\\CMS\\Core\\Resource\\File')) {
				$infoOnClick = 'top.launchView( \'_FILE\', \'' . $fullIdentifier . '\');return false;';
			}
			$cells['info'] = '<a href="#" onclick="' . $infoOnClick . '" title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.info') . '">' . IconUtility::getSpriteIcon('status-dialog-information') . '</a>';
		} else {
			$cells['info'] = IconUtility::getSpriteIcon('empty-empty');
		}

		// delete the file
		if ($fileOrFolderObject->checkActionPermission('delete')) {
			$identifier = $fileOrFolderObject->getIdentifier();
			if ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\Folder) {
				$referenceCountText = BackendUtility::referenceCount('_FILE', $identifier, ' (There are %s reference(s) to this folder!)');
			} else {
				$referenceCountText = BackendUtility::referenceCount('sys_file', $fileOrFolderObject->getUid(), ' (There are %s reference(s) to this file!)');
			}

			if ($GLOBALS['BE_USER']->jsConfirmation(4)) {
				$confirmationCheck = 'confirm(' . GeneralUtility::quoteJSvalue(sprintf($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:mess.delete'), $fileOrFolderObject->getName()) . $referenceCountText) . ')';
			} else {
				$confirmationCheck = '1 == 1';
			}

			$removeOnClick = 'if (' . $confirmationCheck . ') { top.content.list_frame.location.href=top.TS.PATH_typo3+\'tce_file.php?file[delete][0][data]=' . rawurlencode($fileOrFolderObject->getCombinedIdentifier()) . '&vC=' . $GLOBALS['BE_USER']->veriCode() . BackendUtility::getUrlToken('tceAction') .  '&redirect=\'+top.rawurlencode(top.content.list_frame.document.location.pathname+top.content.list_frame.document.location.search);};';

			$cells['delete'] = '<a href="#" onclick="' . htmlspecialchars($removeOnClick) . '"  title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:cm.delete') . '">' . IconUtility::getSpriteIcon('actions-edit-delete') . '</a>';
		} else {
			$cells['delete'] = IconUtility::getSpriteIcon('empty-empty');
		}

		// Hook for manipulating edit icons.
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['fileList']['editIconsHook'])) {
			$cells['__fileOrFolderObject'] = $fileOrFolderObject;
			foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['fileList']['editIconsHook'] as $classData) {
				$hookObject = GeneralUtility::getUserObj($classData);
				if (!$hookObject instanceof \TYPO3\CMS\Filelist\FileListEditIconHookInterface) {
					throw new \UnexpectedValueException(
						'$hookObject must implement interface \\TYPO3\\CMS\\Filelist\\FileListEditIconHookInterface',
						1235225797
					);
				}
				$hookObject->manipulateEditIcons($cells, $this);
			}
			unset($cells['__fileOrFolderObject']);
		}
		// Compile items into a DIV-element:
		return '							<!-- EDIT CONTROLS: -->
											<div class="typo3-editCtrl">
												' . implode('
												', $cells) . '
											</div>';
	}

	/**
	 * Make reference count
	 *
	 * @param \TYPO3\CMS\Core\Resource\File|\TYPO3\CMS\Core\Resource\Folder $fileOrFolderObject Array with information about the file/directory for which to make the clipboard panel for the listing.
	 * @return string HTML
	 * @todo Define visibility
	 */
	public function makeRef($fileOrFolderObject) {
		if ($fileOrFolderObject instanceof \TYPO3\CMS\Core\Resource\FolderInterface) {
			return '-';
		}
		// Look up the file in the sys_refindex.
		// Exclude sys_file_metadata records as these are no use references
		$databaseConnection = $this->getDatabaseConnection();
		$table = 'sys_refindex';
		$rows = $databaseConnection->exec_SELECTgetRows(
			'*',
			$table,
			'ref_table=' . $databaseConnection->fullQuoteStr('sys_file', $table)
				. ' AND ref_uid=' . (int)$fileOrFolderObject->getUid()
				. ' AND deleted=0'
				. ' AND tablename != ' . $databaseConnection->fullQuoteStr('sys_file_metadata', $table)
		);
		return $this->generateReferenceToolTip($rows, '\'_FILE\', ' . GeneralUtility::quoteJSvalue($fileOrFolderObject->getCombinedIdentifier()));
	}

	/**
	 * Get database connection
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}
