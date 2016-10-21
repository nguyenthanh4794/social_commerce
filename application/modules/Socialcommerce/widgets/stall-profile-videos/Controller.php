<?php
class Socialcommerce_Widget_StallProfileVideosController extends Engine_Content_Widget_Abstract {
	protected $_childCount;
	public function indexAction() 
	{
		 // Don't render this if not authorized
	    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

	    if( !Engine_Api::_()->core()->hasSubject() ) {
	      return $this->setNoRender();
	    }
		
	    if(!Engine_Api::_()->hasItemType('video'))
	    {
	      return $this->setNorender();
	    }

	    // Get subject and check auth
	    $this->view->stall = $subject = Engine_Api::_()->core()->getSubject('socialcommerce_stall');

	    if (!$subject -> isViewable() || !Engine_Api::_() -> hasModuleBootstrap('video')) {
            return $this -> setNoRender();
        }

	    $params = array();
	    $params['orderby'] = 'creation_date';
		$params['stall_id'] = $subject -> getIdentity();

	    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('mappings', 'socialcommerce') -> getVideosPaginator($params);

		 // Set item count per page and current page number
	    $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 5));
	    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
	
	    $this -> view -> canCreate = true;

	    $this->getElement()->removeDecorator('Title');

		// Add count to title if configured
	    if( $this->_getParam('titleCount', false) && $paginator->getTotalItemCount() > 0 ) {
	      $this->_childCount = $paginator->getTotalItemCount();
	    }
	}
	public function getChildCount() {
        return $this->_childCount;
    }
}