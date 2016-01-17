<?php

namespace Lobacher\Simpleblog\Controller;

class PostController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * postRepository
     *
     * @var \Lobacher\Simpleblog\Domain\Repository\PostRepository
     * @inject
     */
    protected $postRepository = NULL;
    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * SignalSlotDispatcher
     *
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     * @inject
     */
    protected $signalSlotDispatcher;

    public function initializeAction()
    {
        $action = $this->request->getControllerActionName();
        // check, if a different action than "show" was executed
        if ($action != 'show' && $action != 'ajax') {
            // redirect to the login page (UID=12), if user is not logged-in
            if (!$GLOBALS['TSFE']->fe_user->user['uid']) {
                $this->redirect(NULL, NULL, NULL, NULL, $this->settings['loginpage']);
            }
        }
    }

    /**
     * addForm action - displays a form for adding a post
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function addFormAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post = NULL)
    {
        $this->view->assign('blog', $blog);
        $this->view->assign('post', $post);
        $this->view->assign('tags', $this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\TagRepository')->findAll());
    }

    /**
     * add action - adds a post to the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function addAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
//        $post->setPostdate(new \DateTime());
        //$this->postRepository->add($post);
        $post->setAuthor($this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\AuthorRepository')->findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']));

        $blog->addPost($post);
        $this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\BlogRepository')->update($blog);
        $this->redirect('show', 'Blog', NULL, array('blog' => $blog));
    }

    /**
     * show action - displays a single post
     * *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function showAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
        $this->view->assign('blog', $blog);
        $this->view->assign('post', $post);
    }

    /**
     * updateForm action - displays a form for editing a post
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function updateFormAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
        $this->view->assign('blog', $blog);
        $this->view->assign('post', $post);
        $this->view->assign('tags', $this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\TagRepository')->findAll());
    }

    /**
     * update action - updates a post in the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function updateAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
        $this->postRepository->update($post);
        $this->redirect('show', 'Blog', NULL, array('blog' => $blog));
    }

    /**
     * deleteConfirm action - displays a form for confirming the deletion of ↩
     * a post
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function deleteConfirmAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
        $this->view->assign('blog', $blog);
        $this->view->assign('post', $post);
    }

    /**
     * delete action - deletes a post in the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Blog $blog
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     */
    public function deleteAction(
        \Lobacher\Simpleblog\Domain\Model\Blog $blog,
        \Lobacher\Simpleblog\Domain\Model\Post $post)
    {
        $blog->removePost($post);
        $this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\BlogRepository')->update($blog);
        $this->postRepository->remove($post);
        $this->redirect('show', 'Blog', NULL, array('blog' => $blog));
    }

    /**
     * Ajax action - deletes a post in the repository
     *
     * @param \Lobacher\Simpleblog\Domain\Model\Post $post
     * @param \Lobacher\Simpleblog\Domain\Model\Comment $comment
     *
     * @return bool|string
     */
    public function ajaxAction(\Lobacher\Simpleblog\Domain\Model\Post $post, \Lobacher\Simpleblog\Domain\Model\Comment $comment = NULL)
    {

        if ($comment->getComment() == "") return FALSE;
        // set datetime of comment and add comment to Post
        $comment->setCommentdate(new \DateTime());
        $post->addComment($comment);

        // signal for comments
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            'beforeCommentCreation',
            array($comment,$post)
        );

        $this->postRepository->update($post);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $json[$comment->getUid()] = array(
                'comment' => $comment->getComment(),
                'commentdate' => $comment->getCommentdate()
            );
        }
        return json_encode($json);
    }

}