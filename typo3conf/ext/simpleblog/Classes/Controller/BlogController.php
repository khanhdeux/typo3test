<?php

namespace Lobacher\Simpleblog\Controller;

class BlogController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * blogRepository
     *
     * @var \Lobacher\Simpleblog\Domain\Repository\BlogRepository
     * @inject
     */
    protected $blogRepository;
    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \SJBR\StaticInfoTables\Domain\Repository\CountryRepository
     */
    protected $countryRepository;

    /**
     * Dependency injection of the Country Repository
     *
     * @param \SJBR\StaticInfoTables\Domain\Repository\CountryRepository $countryRepository
     * @return void
     */
    public function injectCountryRepository(\SJBR\StaticInfoTables\Domain\Repository\CountryRepository $countryRepository) {
        $this->countryRepository = $countryRepository;
    }

    public function initializeAction()
    {
        if ($this->arguments->hasArgument('blog')) {
            $this->arguments->getArgument('blog')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('image', 'array');
        }
    }

    /**
     * action
     * @return void
     *
     */
    public function listAction()
    {
        if ($this->request->hasArgument('search')) {
            $search = $this->request->getArgument('search');
        }
        $limit = ($this->settings['blog']['max']) ?: NULL;
        $this->view->assign('blogs', $this->blogRepository->findSearchForm($search, $limit));
        $this->view->assign('search', $search);

        $inputArray = array(
            'title' => 'Rocky wants to go for a stroll!',
            'postdate' => '2013-12-28T07:56:00+00:00'
        );
        $propertyMapper = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Property\\PropertyMapper');
        $post = $propertyMapper->convert($inputArray, 'Lobacher\Simpleblog\Domain\Model\Post');
//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($post);/

    }

    /**
     * Initialize action method validators
     *
     * @return void
     */
    protected function initializeActionMethodValidators()
    {
        if ($this->actionMethodName == 'addAction') {
            parent::initializeActionMethodValidators();

            $blog = $this->arguments['blog'];
            $validator = $blog->getValidator();

            $requestArguments = $this->request->getArguments();
            $agb = $requestArguments['acceptTermsAndConditions'];

            $termsAndConditions =
                \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Lobacher\Simpleblog\Validation\Validator\TermsAndConditionsValidator',
                    array('value' => $agb)
                );
            $validator->addValidator($termsAndConditions);
        }
    }

    /**
     * add action - adds a blog to the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @validate $blog Lobacher.Simpleblog:Facebook
     */
    public function addAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {

        $this->addFlashMessage(
            'Blog successfully created!',
            'Status',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::OK, TRUE
        );

        $this->blogRepository->add($blog);
        $this->redirect('list');
    }

    /**
     * addForm action - displays a form for adding a blog
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function addFormAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog = NULL)
    {
        $this->view->assign('blog', $blog);
        $this->view->assign('countries', $this->getCountries());
    }

    /**
     * show action - displays a single blog
     *
     * 7.2 Display a Blog (Read)
     * Lobacher, Schams · TYPO3 Extbase 201
     * Copy for Pietsch Andi <andi.pietsch@arrabiata.de>
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function showAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * updateForm action - displays a form for editing a blog
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function updateFormAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * update action - updates a blog in the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function updateAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->blogRepository->update($blog);
        $this->redirect('list');
    }

    /**
     * delete action - deletes a blog in the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function deleteAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->blogRepository->remove($blog);
        $this->redirect('list');
    }

    /**
     * deleteConfirm action - displays a form for confirming the deletion ↩
     * of a blog
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function deleteConfirmAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * RSS Feed for the posts of one blog
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function rssAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * Get EU countries
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getCountries() {
        $countries = $this->countryRepository->findByEuMember(1);
        return $countries;
    }
}

?>