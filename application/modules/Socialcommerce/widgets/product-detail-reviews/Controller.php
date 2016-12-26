<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 12/15/2016
 * Time: 8:13 AM
 */
class Socialcommerce_Widget_ProductDetailReviewsController extends Engine_Content_Widget_Abstract
{
    protected $_childCount;
    public function indexAction()
    {
        if (!Engine_Api::_()->core()->hasSubject()) {
            return $this->setNoRender();
        }

        // Get subject and check auth
        $subject = Engine_Api::_()->core()->getSubject('socialcommerce_product');
        $viewer = Engine_Api::_()->user()->getViewer();

        $params = array(
            'item_id' => $subject->getIdentity(),
            'type' => 'product',
        );

        $tableReviews = Engine_Api::_()->getDbTable('reviews', 'socialcommerce');
        $this->view->reviews = $reviews = $tableReviews->getReviewsPaginator($params);

        $select = $tableReviews->select()->from($tableReviews->info('name'))
            ->where('item_id = ?', $subject->getIdentity())
            ->where('user_id = ?', $viewer->getIdentity())
            ->where('type = \'product\'');

        $this->view->my_review = $my_review = $tableReviews->fetchRow($select);
        $this->_childCount = $reviews->getTotalItemCount();
    }

    public function getChildCount()
    {
        return $this->_childCount;
    }
}