<?php

namespace Vendor\Guestbook\Controller;

class CommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * commentRepository
     *
     * @var \Vendor\Guestbook\Domain\Repository\CommentRepository
     * @inject
     */
    protected $commentRepository;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * action
     *
     * @return void
     *
     */
    public function listAction()
    {

        if ($authorID = $GLOBALS['TSFE']->fe_user->user['uid']) {

            $author = $this->objectManager->get('Vendor\\Guestbook\\Domain\\Repository\\AuthorRepository')->findOneByUid($authorID);
            $this->view->assign('author', $author);

        }

        $this->view->assign('comments', $this->commentRepository->findAll());
    }

    /**
     * Ajax action - Add more comments
     *
     * @param \Vendor\Guestbook\Domain\Model\Comment $comment
     *
     * @return bool|string
     */
    public function ajaxAddCommentAction(\Vendor\Guestbook\Domain\Model\Comment $comment = NULL)
    {

        if ($comment->getComment() == "") return FALSE;
        // set datetime of comment and add comment to Post
        $comment->setCommentdate(new \DateTime());
        $author = $this->objectManager->get('Vendor\\Guestbook\\Domain\\Repository\\AuthorRepository')->findOneByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        $comment->setAuthor($author);

        $this->commentRepository->add($comment);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
        $comments = $this->commentRepository->findAll();

        foreach ($comments as $comment) {

            $json[$comment->getUid()] = array(
                'comment' => $comment->getComment(),
                'commentdate' => $comment->getCommentdate(),
                'author' => array(
                    'fullname' => $author->getFullname(),
                    'email' => $author->getEmail()
                )
            );

        }

        return json_encode($json);

    }

}

?>