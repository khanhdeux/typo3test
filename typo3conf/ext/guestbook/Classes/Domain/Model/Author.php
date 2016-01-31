<?php
namespace Vendor\Guestbook\Domain\Model;

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
 * News
 */
class Author extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * username
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $username = '';

	/**
	 * fullname
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $fullname = '';

	/**
	 * email
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $email = '';

	/**
	 * image
	 *
	 * @var string
	 */
	protected $image = '';

	/**
	 * password
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $password = '';

//	/**
//	 * usergroup
//	 *
//	 * @var int
//	 */
//	protected $usergroup = 0;

    /**
     * options
     *
     * @var string
     */
    protected $options = '';

    /**
     * Tax Id
     *
     * @var string
     */
    protected $taxId = '';

    /**
     * Disable
     *
     * @var int
     */
    protected $disable = 0;

	/**
	 * __construct
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties
	 * Do not modify this method!
	 * It will be rewritten on each save in the extension builder
	 * You may modify the constructor of this class instead
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		
	}

	/**
	 * Returns the fullname
	 *
	 * @return string fullname
	 */
	public function getFullname() {
		return $this->fullname;
	}

	/**
	 * Sets the fullname
	 *
	 * @param string $fullname
	 * @return void
	 */
	public function setFullname($fullname) {
		$this->fullname = $fullname;
	}

	/**
	 * Returns the email
	 *
	 * @return string email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Returns the image
	 *
	 * @return string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param string $image
	 * @return void
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Returns the password
	 *
	 * @return string $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Sets the password
	 *
	 * @param string $password
	 * @return void
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * Returns the username
	 *
	 * @return string $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Sets the username
	 *
	 * @param string $username
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

//	/**
//	 * Returns the usergroup
//	 *
//	 * @return int $usergroup
//	 */
//	public function getUsergroup() {
//		return $this->usergroup;
//	}
//
//	/**
//	 * Sets the usergroup
//	 *
//	 * @param int $usergroup
//	 * @return void
//	 */
//	public function setUsergroup($usergroup) {
//		$this->usergroup = $usergroup;
//	}

    /**
     * @return array $options
     */
    public function getOptions()
    {
        return unserialize($this->options);
    }

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = serialize($options);
    }

    /**
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * @param string $taxId
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
    }

    /**
     * @return int
     */
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * @param int $disable
     */
    public function setDisable($disable)
    {
        $this->disable = $disable;
    }

}