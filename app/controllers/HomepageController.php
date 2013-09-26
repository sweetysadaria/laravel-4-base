<?php

/**
 * Home page controller
 */

class HomepageController extends BaseController {

	/**
	 * User Model
	 * @var  User
	 */
	protected $user;

	public function __construct(User $user) {
		parent::__construct();

		$this->user = $user;
	}

	/**
	 * Returns the Index View
	 * @return View
	 */
	public function getIndex() {

		// Show that sweet sweet homepage
		return View::make('site/index');
	}
}