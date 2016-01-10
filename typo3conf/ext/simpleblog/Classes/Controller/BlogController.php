<?php

namespace Lobacher\Simpleblog\Controller;

class BlogController extends \TYPO3\CMS\Extbase\Mvc\Controller
\ActionController
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
     * action
     * @return void
     *
     */
    public function listAction()
    {
        if ($this->request->hasArgument('search')){
            $search = $this->request->getArgument('search');
        }
        $limit = ($this->settings['blog']['max']) ?: NULL;
        $this->view->assign('blogs', $this->blogRepository->findSearchForm($search, $limit));
        $this->view->assign('search', $search);
    }

    /**
     * add action - adds a blog to the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @validate $blog Lobacher.Simpleblog:Facebook
     */
    public function addAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog)
    {
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
    of a blog
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     */
    public function deleteConfirmAction(\Lobacher\Simpleblog\Domain\Model\Blog $blog) {
        $this->view->assign('blog',$blog);
    }

}

?>