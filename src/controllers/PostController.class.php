<?php

require_once __MODEL_PATH . 'post/PostManager.class.php';

class PostController extends Controller {

	/**
	 * @var Layout
	 */
	protected $moLayout;

	public function __construct() {
		$this->moLayout = new Layout(__LAYOUT_PATH . '/Layout.phtml');
	}

	public function index() {
		$this->last();
	}

	public function last($_args) {
		$iPage = isset($_args[0]) && ctype_digit($_args[0])?$_args[0]:1;
		$oView = new View('Posts.phtml');
		$oPostManager = new PostManager();
		$oView->posts = $oPostManager->getLastPosts(PAGE_SIZE, $iPage);
		$oView->currentpage = $iPage;
		$oView->baseurl = '/Post/Last';
		
		$this->moLayout->setPlaceholder('content', $oView->execute());
		$this->render();
	}

	public function tag($_args) {
		$iPage = isset($_args[1]) && ctype_digit($_args[1])?$_args[1]:1;
		$oView = new View('Posts.phtml');
		$oPostManager = new PostManager();
		$oView->posts = $oPostManager->getLastPostsByTag($_args[0], PAGE_SIZE, $iPage);
		$oView->currentpage = $iPage;
		$oView->baseurl = '/Post/Tag/'.$_args[0];

		$this->moLayout->setPlaceholder('content', $oView->execute());
		$this->render();
	}

	public function write($_args) {
		if (isset($_args['title'])) {
			$oPostManager = new PostManager();
			if ($oPostManager->insertPost($_args['title'], $_args['text'], $_args['tags'])) {
				header('Location: /');
			} else {
				$this->write(array());
			}
		} else {
			$oView = new View('Write.phtml');
			$oPostManager = new PostManager();
			$oView->posts = $oPostManager->getLastPosts();;
			$this->moLayout->setPlaceholder('content', $oView->execute());
			$this->render();
		}
	}

	protected function render() {
		$this->moLayout->render();
	}

}

