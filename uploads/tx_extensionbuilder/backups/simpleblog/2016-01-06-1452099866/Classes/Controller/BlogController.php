<?php

namespace Lobcher\Simpleblog\Controller;

class BlogController extends \TYPO3\CMS\Extbase\Mvc\Controller
\ActionController
{
    /**
     * blogRepository
     *
     * @var \Lobcher\Simpleblog\Domain\Repository\BlogRepository
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
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function addAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog)
    {

/*        $blog = $this->objectManager->get('\\Lobcher\\Simpleblog\\Domain\\Model\\Blog');
        $blog->setTitlle('This new Blog');*/

        $this->blogRepository->add($blog);
        $this->redirect('list');
    }

    /**
     * addForm action - displays a form for adding a blog
     *
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function addFormAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog = NULL)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * show action - displays a single blog
     *
     * 7.2 Display a Blog (Read)
     * Lobcher, Schams · TYPO3 Extbase 201
     * Copy for Pietsch Andi <andi.pietsch@arrabiata.de>
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function showAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * updateForm action - displays a form for editing a blog
     *
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function updateFormAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->view->assign('blog', $blog);
    }

    /**
     * update action - updates a blog in the repository
     *
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function updateAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->blogRepository->update($blog);
        $this->redirect('list');
    }

    /**
     * delete action - deletes a blog in the repository
     *
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function deleteAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog)
    {
        $this->blogRepository->remove($blog);
        $this->redirect('list');
    }

    /**
     * deleteConfirm action - displays a form for confirming the deletion ↩
    of a blog
     *
     * @param \Lobcher\Simpleblog\Domain\Model\Blog $blog
     */
    public function deleteConfirmAction(\Lobcher\Simpleblog\Domain\Model\Blog $blog) {
        $this->view->assign('blog',$blog);
    }

}

?>