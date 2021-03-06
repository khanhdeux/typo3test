<?php
namespace Vendor\Guestbook\Domain\Repository;

    /***************************************************************
     *
     *  Copyright notice
     *
     *  (c) 2016 Quoc Khanh Nguyen <khanh.nguyen@arrabiata.de>, Arrabiata
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/

/**
 * The repository for News
 */
class AuthorRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    protected $defaultOrderings = array('fullname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

//    public function findHiddenByUid($uid) {
//        $query = $this->createQuery();
//        $query->getQuerySettings()->setStoragePageIds(array(9));
//        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
//        $query->matching($query->equals('uid', $uid));
//        return $query->execute()->getFirst();
//    }
}