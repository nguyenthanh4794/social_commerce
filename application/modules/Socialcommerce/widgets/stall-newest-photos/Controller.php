<?php

class Socialcommerce_Widget_BusinessNewestPhotosController extends Engine_Content_Widget_Abstract
{
	public function indexAction()
	{
		// Don't render this if not authorized
		$viewer = Engine_Api::_()->user()->getViewer();
		if( !Engine_Api::_()->core()->hasSubject() ) {
			return $this->setNoRender();
		}

		// Get subject and check auth
		$subject = Engine_Api::_()->core()->getSubject('socialcommerce_stall');
		if (!$subject -> isViewable() || !$subject -> getPackage() -> checkAvailableModule('album')) {
			return $this -> setNoRender();
		}
		
		// Get paginator
		$album = $subject->getSingletonAlbum();
		$photos = $album->getCollectibles(true);
		$this->view->paginator = $paginator = Zend_Paginator::factory($photos);
		
		// Set item count per page and current page number
		$paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 1));
		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
		
		// Do not render if nothing to show and cannot upload
		if( $paginator->getTotalItemCount() <= 0 ) {
			return $this->setNoRender();
		}
	}
}