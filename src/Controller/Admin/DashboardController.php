<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
/**
 * Dashboard Controller
 */

class DashboardController extends AppController
{


	public function initialize()
    {
        parent::initialize();

    }

	public function index()
	{
		$user = $this->Auth->user();
		
		$this->set(compact('user'));
	}
}