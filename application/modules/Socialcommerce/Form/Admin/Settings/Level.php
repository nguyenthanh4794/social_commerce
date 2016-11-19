<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 10:29 PM
 */
class Socialcommerce_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
    public function getSettingsValues() {
        $values = $this->getValues();
        $stallValues = array();
        $listingValues = array();
        foreach($values as $key => $val) {
            $data = explode('_', $key, 2);
            if ($data[0] == 'stall') {
                $stallValues[$data[1]] = $val;
            } else if ($data[0] == 'listing') {
                $listingValues[$data[1]] = $val;
            }
        }
        return array('stall' => $stallValues, 'listing' => $listingValues);
    }
    public function init() {
        $this
            ->setTitle('Member Level Settings')
            ->setDescription('SOCIALCOMMERCE_SETTINGS_LEVEL_DESCRIPTION');

        $levels = array();
        $table  = Engine_Api::_()->getDbtable('levels', 'authorization');
        foreach ($table->fetchAll($table->select()) as $row) {
            $levels[$row['level_id']] = $row['title'];
        }

        $roles = array(
            'everyone' => 'Everyone',
            'registered' => 'All Registered Members',
            'owner_network' => 'Friends and Networks',
            'owner_member' => 'Friends Only',
            'owner' => 'Just Me'
        );

        $this->addElement('Select', 'level_id', array(
            'label' => 'Member Level',
            'multiOptions' => $levels,
            'ignore' => true
        ));
        if( !$this->isPublic() ) {
            $this->addElement('Radio', 'stall_view', array(
                'label' => 'Allow Viewing Details of Stalls?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view Stalls',
                    0 => 'No, do not allow users to view Stalls'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'stall_comment', array(
                'label' => 'Allow Commenting on Stalls?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to comment on Stalls',
                    0 => 'No, do not allow users to comment on Stalls'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'stall_follow', array(
                'label' => 'Allow Follow Stall?',
                'multiOptions' => array(
                    1 => 'Yes, allow following any stall',
                    0 => 'No, do not allow following any stall'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'stall_approve', array(
                'label' => 'Stall auto-approved after paying successfully',
                'multiOptions' => array(
                    0 => 'No, stalls must be approved by admin to be published',
                    1 => 'Yes, stalls automatically approved to be published',
                ),
                'value' => 0,
            ));

            $this->addElement('Integer', 'stall_max', array(
                'label' => 'Maximum number of Stalls can own',
                'description' => '0 mean unlimited',
                'value' => 0,
                'validators' => array(
                    new Engine_Validate_AtLeast(0),
                ),
            ));
            $this->stall_max->getDecorator('Description')->setOption('placement', 'APPEND');

            $this->addElement('Radio', 'rating', array(
                'label' => 'Can Rate and Review Stalls',
                'multiOptions' => array(
                    1 => 'Yes, allow rating and reviewing any Stalls',
                    1 => 'Yes, allow upload photos to Listings',
                    0 => 'No, do not allow rating and reviewing'
                ),
                'value' => 1,
            ));

            $roles_values = array('everyone', 'registered', 'owner_network', 'owner_member', 'owner');
            $auths = array('view', 'photo', 'video', 'comment');
            foreach ($auths as $auth) {
                $this->addElement('MultiCheckbox', 'stall_auth_'.$auth, array(
                    'label' => ucfirst($auth).' Privacy',
                    'description' => 'SOCIALCOMMERCE_AUTH_'.strtoupper($auth).'_DESCRIPTION',
                    'multiOptions' => $roles,
                    'value' => $roles_values
                ));
            }

            $this->addElement('Radio', 'listing_view', array(
                'label' => 'Allow Viewing Details of Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view Listings',
                    0 => 'No, do not allow users to view Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'listing_comment', array(
                'label' => 'Allow Commenting on Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to comment on Listings',
                    0 => 'No, do not allow users to comment on Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'listing_follow', array(
                'label' => 'Allow Follow Stall?',
                'multiOptions' => array(
                    1 => 'Yes, allow following any listing',
                    0 => 'No, do not allow following any listing'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'listing_approve', array(
                'label' => 'Stall auto-approved after paying successfully',
                'multiOptions' => array(
                    0 => 'No, listings must be approved by admin to be published',
                    1 => 'Yes, listings automatically approved to be published',
                ),
                'value' => 0,
            ));

            $this->addElement('Integer', 'listing_max', array(
                'label' => 'Maximum number of Listings can own',
                'description' => '0 mean unlimited',
                'value' => 0,
                'validators' => array(
                    new Engine_Validate_AtLeast(0),
                ),
            ));
            $this->listing_max->getDecorator('Description')->setOption('placement', 'APPEND');

            $this->addElement('Radio', 'listing_rating', array(
                'label' => 'Can Rate and Review Listings',
                'multiOptions' => array(
                    1 => 'Yes, allow rating and reviewing any Listings',
                    1 => 'Yes, allow upload photos to Listings',
                    0 => 'No, do not allow rating and reviewing'
                ),
                'value' => 1,
            ));

            $roles_values = array('everyone', 'registered', 'owner_network', 'owner_member', 'owner');
            $auths = array('view', 'photo', 'video', 'comment');
            foreach ($auths as $auth) {
                $this->addElement('MultiCheckbox', 'listing_auth_'.$auth, array(
                    'label' => ucfirst($auth).' Privacy',
                    'description' => 'SOCIALCOMMERCE_AUTH_'.strtoupper($auth).'_DESCRIPTION',
                    'multiOptions' => $roles,
                    'value' => $roles_values
                ));
            }
        }
        else {
            $this->addElement('Radio', 'stall_view', array(
                'label' => 'Allow Viewing Details of Stalls?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view Stalls',
                    0 => 'No, do not allow users to view Stalls'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'listing_view', array(
                'label' => 'Allow Viewing Details of Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view Listings',
                    0 => 'No, do not allow users to view Listings'
                ),
                'value' => 1,
            ));
        }

        $this->addElement('Button', 'submit_btn', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }
}