<?php

namespace Lobacher\Simpleblog\Controller;

class PostController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * postRepository
     *
     * @var \Lobacher\Simpleblog\Domain\Repository\BlogRepository
     * @inject
     */
    protected $blogRepository = NULL;
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
        $post->setPostdate(new \DateTime());
        //$this->postRepository->add($post);
        $post->setAuthor($this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\AuthorRepository')->findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']));

        $blog->addPost($post);
        $this->objectManager->get('Lobacher\\Simpleblog\\Domain\\Repository\\BlogRepository')->update($blog);
        $this->redirect('show', 'Blog', NULL, array('blog' => $blog));
    }

    /**
     * Initialize Add action
     */
    public function initializeAddAction()
    {
        if ($this->request->hasArgument('blog')) {
            $blog = $this->request->getArgument('blog');
            if (!empty($blog['uid'])) {
                $blog['__identity'] = $blog['uid'];
                unset($blog['uid']);
            }
        }

        $this->request->setArgument('blog', $blog);

        $this->arguments['blog']
            ->getPropertyMappingConfiguration()
            ->allowAllProperties()
            ->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
            ->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);

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
     * Initialize Update action
     */
    public function initializeUpdateAction()
    {
        if ($this->request->hasArgument('blog')) {
            $blog = $this->request->getArgument('blog');
            if (!empty($blog['uid'])) {
                $blog['__identity'] = $blog['uid'];
                unset($blog['uid']);
            }
        }

        $this->request->setArgument('blog', $blog);

        $this->arguments['blog']
            ->getPropertyMappingConfiguration()
            ->allowAllProperties()
            ->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
            ->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);

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
        $this->blogRepository->update($blog);
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

        file_put_contents('./debug.txt', print_r($comment, true), FILE_APPEND);
        file_put_contents('./debug.txt', print_r("\n-----------------\n", true), FILE_APPEND);
        if ($comment->getComment() == "") return FALSE;
        // set datetime of comment and add comment to Post
        $comment->setCommentdate(new \DateTime());
        $post->addComment($comment);

        // signal for comments
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            'beforeCommentCreation',
            array($comment, $post)
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

    /**
     * Ajax action - add address to textarea
     *
     * @return bool|string
     */
    public function ajaxAddressAction()
    {
        $responseArray = [
            1 => [
                'firstName' => 'firstname1',
                'lastName' => 'lastname1'
            ],
            2 => [
                'firstName' => 'firstname2',
                'lastName' => 'lastname2'
            ]
        ];

        $address = $this->request->getArgument('address');
        return json_encode($responseArray[$address]);
    }

}