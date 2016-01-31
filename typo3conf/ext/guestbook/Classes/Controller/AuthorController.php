<?php

namespace Vendor\Guestbook\Controller;

class AuthorController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * authorRepository
     *
     * @var \Vendor\Guestbook\Domain\Repository\AuthorRepository
     * @inject
     */
    protected $authorRepository;
    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * updateForm action - displays a form for editing author
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     * @ignorevalidation
     */
    public function updateFormAction(\Vendor\Guestbook\Domain\Model\Author $author)
    {
        $this->view->assign('author', $author);
    }

    /**
     * initialize update action
     *
     * @return void
     */
    public function initializeUpdateAction()
    {
        if ($this->arguments->hasArgument('author')) {
            $this->arguments->getArgument('author')->getPropertyMappingConfiguration()->allowAllProperties();
            $this->arguments->getArgument('author')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('options', 'array');
        }
    }

    /**
     * update action - updates an author in the repository
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     */
    public function updateAction(\Vendor\Guestbook\Domain\Model\Author $author)
    {

        $this->fillInAuthData($author);
        $this->authorRepository->update($author);
        $this->redirect('updateForm', 'Author', NULL, array('author' => $author));
    }

    /**
     * Upload files.
     *
     * @return void
     */
    public function uploadAction()
    {

        $overwriteExistingFiles = NO;

        $data = array();
        $namespace = key($_FILES);

        $dirName = 'profile_photo';

        if (!is_dir('fileadmin/' . $dirName)) {
            mkdir('fileadmin/' . $dirName, 0777, true);
        }

        $targetFalDirectory = '1:/profile_photo/';

        // Register every upload field from the form:
        $this->registerUploadField($data, $namespace, 'image', $targetFalDirectory);

        // Initializing:
        /** @var \TYPO3\CMS\Core\Utility\File\ExtendedFileUtility $fileProcessor */
        $fileProcessor = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\ExtendedFileUtility');
        $fileProcessor->init(array(), $GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);
        $fileProcessor->setActionPermissions(array('addFile' => TRUE));
        $fileProcessor->dontCheckForUnique = $overwriteExistingFiles ? 1 : 0;

        // Actual upload
        $fileProcessor->start($data);
        $result = $fileProcessor->processData();

        // Do whatever you want with $result (array of File objects)
        foreach ($result['upload'] as $files) {

            /** @var \TYPO3\CMS\Core\Resource\File $file */
            $file = $files[0];    // Single element array due to the way we registered upload fields
            return $file;
        }

        return false;
    }

    /**
     * Registers an uploaded file for TYPO3 native upload handling.
     *
     * @param array &$data
     * @param string $namespace
     * @param string $fieldName
     * @param string $targetDirectory
     * @return void
     */
    protected function registerUploadField(array &$data, $namespace, $fieldName, $targetDirectory = '1:/_temp_/')
    {

        if (!isset($data['upload'])) {
            $data['upload'] = array();
        }

        $counter = count($data['upload']) + 1;

        $keys = array_keys($_FILES[$namespace]);

        foreach ($keys as $key) {
            $_FILES['upload_' . $counter][$key] = $_FILES[$namespace][$key][$fieldName];
        }
        $data['upload'][$counter] = array(
            'data' => $counter,
            'target' => $targetDirectory,
        );
    }

    /**
     * addForm action - displays a form for adding a an author
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     */
    public function addFormAction(\Vendor\Guestbook\Domain\Model\Author $author = NULL)
    {
        $this->view->assign('author', $author);
    }

    /**
     * initialize add action
     *
     * @return void
     */
    public function initializeAddAction()
    {
        if ($this->arguments->hasArgument('author')) {
            $this->arguments->getArgument('author')->getPropertyMappingConfiguration()->allowAllProperties();
            $this->arguments->getArgument('author')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('options', 'array');
        }
    }

    /**
     * add action - adds an author to the repository
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     */
    public function addAction(\Vendor\Guestbook\Domain\Model\Author $author)
    {
        $this->fillInAuthData($author);
        $author->setDisable(1);
        $this->authorRepository->add($author);
        $baseURL = $this->request->getBaseUri();
        $this->persistenceManager->persistAll();
        $emailConfirmationURL =  $baseURL . $this->getControllerContext()->getUriBuilder()->reset()->uriFor('activate', array('author' => $author), 'Author', $this->getControllerContext()->getRequest()->getControllerExtensionName());

        $this->sendEmail($emailConfirmationURL);

        file_put_contents('./debug.txt', print_r("\n-----------------\n", true), FILE_APPEND);
        file_put_contents('./debug.txt', print_r($emailConfirmationURL, true), FILE_APPEND);

        $this->redirect('list', 'Comment', NULL, NULL);
    }

    /**
     * Fill in data to Author object
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     */
    private function fillInAuthData(\Vendor\Guestbook\Domain\Model\Author $author)
    {
        $userGroup = ($this->settings['usergroup']) ? $this->settings['usergroup'] : '';
        $author->setUsergroup($userGroup);
        $this->setAuthorImage($author);
        $author->setPassword($this->getAuthorPassword($author));
    }

    /**
     * Set auth Image
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     */
    private function setAuthorImage(\Vendor\Guestbook\Domain\Model\Author $author)
    {

        if ($fileData = $this->uploadAction()) {
            $author->setImage($fileData->getName());
        };
    }

    /**
     * Get password from request
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     * @return bool|string
     */
    private function getAuthorPassword(\Vendor\Guestbook\Domain\Model\Author $author)
    {

        if ($this->checkAuthorPassword($author)) {
            return $this->getHashedPassword($author->getPassword());
        }

        return $author->getPassword();
    }

    /**
     *  Check if password needs to be updated
     *
     * @param \Vendor\Guestbook\Domain\Model\Author $author
     * @return bool|string
     */
    private function checkAuthorPassword(\Vendor\Guestbook\Domain\Model\Author $author)
    {

        $success = TRUE;

        $dataProperties = $author->_getCleanProperties();

        if (isset($dataProperties['password'])) {

            $dataPass = $dataProperties['password'];

            if ($dataPass === $author->getPassword()) {
                $success = FALSE;
            }
        }

        return $success;
    }

    /**
     * Get hashed password
     *
     * @param string $password
     * @return string
     */
    private function getHashedPassword($password)
    {

        $saltedPassword = '';

        if (\TYPO3\CMS\Saltedpasswords\Utility\SaltedPasswordsUtility::isUsageEnabled('FE')) {

            $objSalt = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance(NULL);

            if (is_object($objSalt)) {
                $saltedPassword = $objSalt->getHashedPassword($password);
            }
        }

        return $saltedPassword;
    }

    /**
     * initialize active action
     *
     * @return void
     */
    public function initializeActivateAction()
    {
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(FALSE);
        $querySettings->setIgnoreEnableFields(TRUE);
        $querySettings->setEnableFieldsToBeIgnored(array('disabled'));
        $querySettings->setIncludeDeleted(TRUE);
        $this->authorRepository->setDefaultQuerySettings($querySettings);
    }

    /**
     * Send activation email
     *
     * @param $url
     */
    private function sendEmail($url) {

        $to      = 'khanhdeux@gmail.com';
        $subject = 'the subject';
        $message = 'Activation link:' . $url;
        $headers = 'From: khanhdeux@arrabiata.de' . "\r\n" .
            'Reply-To: khanhdeux@arrabiata.de' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

    }

    /**
     * Activate the author - activate the author: switch disable -> 0
     *
     * @param string $author
     */
    public function activateAction($author)
    {
        /** @var \Vendor\Guestbook\Domain\Model\Author $author */
        $author = $this->authorRepository->findByUid((int)$author);
        if($author->getDisable() == 0) $this->redirect('list', 'Comment', NULL, NULL);
        $author->setDisable(0);
        $this->authorRepository->update($author);
        $this->view->assign('author', $author);
    }
}

?>