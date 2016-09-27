<?php

/**
 * Created by PhpStorm.
 * User: Nguyen Thanh
 * Date: 9/26/2016
 * Time: 10:29 PM
 */
class Socialcommerce_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
    public function init() {
        $this
            ->setTitle('Member Level Settings')
            ->setDescription('SOCIALCOMMERCE_SETTINGS_LEVEL_DESCRIPTION');

        $levels = array();
        $table  = Engine_Api::_()->getDbtable('levels', 'authorization');
        foreach ($table->fetchAll($table->select()) as $row) {
            $levels[$row['level_id']] = $row['title'];
        }

        $this->addElement('Select', 'level_id', array(
            'label' => 'Member Level',
            'multiOptions' => $levels,
            'ignore' => true
        ));
        if( !$this->isPublic() ) {
            $this->addElement('Heading','payment_method', array(
                'label' => 'Payment Method',
                'description' => 'Choose what payment method enable for users in this group can choose'
            ));

            $this->addElement('Checkbox', 'is_online_payment', array(
                'label' => 'Online Payment'
            ));

            $this->addElement('Select', 'plan', array(
                'description' => 'Choose Plan',
                'multiOptions' => array(
                    '1' => 'Plan 1',
                    '2' => 'Plan 2',
                )
            ));

            $this->addElement('Checkbox', 'is_offline_payment', array(
                'label' => 'Offline Payment/Cash On Delivery'
            ));

            $this->addElement('Integer', 'publish_fee', array(
                'description' => 'Publishing Fee',
                'validators' => array(
                    new Engine_Validate_AtLeast(0),
                ),
            ));

            $this->addElement('Integer', 'commission_fee', array(
                'description' => '- Commission Fee',
                'validators' => array(
                    new Engine_Validate_AtLeast(0),
                ),
            ));

            $this->addElement('Integer', 'feature_fee', array(
                'description' => 'Feature Listing Fee',
                'validators' => array(
                    new Engine_Validate_AtLeast(0),
                ),
            ));

            $this->addElement('Radio', 'view', array(
                'label' => 'Allow Viewing Details of Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view listings',
                    0 => 'No, do not allow users to view listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'create', array(
                'label' => 'Allow Creation of Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to create new listing',
                    0 => 'No, do not allow users to create new listing'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'edit', array(
                'label' => 'Allow Editing of Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to edit their own listings',
                    0 => 'No, do not allow users to edit their own listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'delete', array(
                'label' => 'Allow Deletion of Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to delete their own listings',
                    0 => 'No, do not allow users to delete their own listings'
                ),
                'value' => 1,
            ));


            $this->addElement('Radio', 'comment', array(
                'label' => 'Allow Commenting on Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to comment on listings',
                    0 => 'No, do not allow users to comment on listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'follow', array(
                'label' => 'Allow follow Listing Owner?',
                'multiOptions' => array(
                    1 => 'Yes, allow follow on listings owner',
                    0 => 'No, do not allow follow on listings owner'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'theme', array(
                'label' => 'Allow select theme for own Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow select theme for own Listings',
                    0 => 'No, do not allow select theme for own Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'upload', array(
                'label' => 'Allow upload photos to Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow upload photos to Listings',
                    0 => 'No, do not allow upload photos to Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'upload', array(
                'label' => 'Allow upload videos to Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow upload videos to Listings',
                    0 => 'No, do not allow upload videos to Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'discuss', array(
                'label' => 'Allow add discussion to Listings?',
                'multiOptions' => array(
                    1 => 'Yes, allow add discussion to Listings',
                    0 => 'No, do not allow add discussion to Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'approve', array(
                'label' => 'Approve Listings before they are publicly displayed?',
                'multiOptions' => array(
                    1 => 'Yes, listings must be approved before displaying',
                    0 => 'No, listing will be shown immediately after creating'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'print', array(
                'label' => 'Allow print Listing?',
                'multiOptions' => array(
                    1 => 'Yes, listings must be approved before displaying',
                    0 => 'No, listing will be shown immediately after creating'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'import', array(
                'label' => 'Allow import own Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow import own Listings',
                    0 => 'No, do not allow import own Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'export', array(
                'label' => 'Allow export own Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow export own Listings',
                    0 => 'No, do not allow export own Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Integer', 'max_listings', array(
                'label' => 'Maximum listings can be added',
                'description' => '0 mean unlimited',
                'value' => 10,
            ));

            $this->addElement('Radio', 'report', array(
                'label' => 'Allow report Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow report Listings',
                    0 => 'No, do not allow report Listings'
                ),
                'value' => 1,
            ));

            $this->addElement('Radio', 'rating', array(
                'label' => 'Allow rate Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow rate Listings',
                    0 => 'No, do not allow rate Listings'
                ),
                'value' => 1,
            ));

            $roles = array(
                'everyone' => 'Everyone',
                'registered' => 'All Registered Members',
                'owner_network' => 'Friends and Networks',
                'owner_member' => 'Friends Only',
                'owner' => 'Just Me'
            );

            $roles_values = array('everyone', 'registered', 'owner_network', 'owner_member', 'owner');
            $auths = array('view', 'comment', 'share', 'photo', 'video', 'discussion');
            foreach ($auths as $auth) {
                $this->addElement('MultiCheckbox', 'auth_'.$auth, array(
                    'label' => ucfirst($auth).' Privacy',
                    'description' => 'SOCIALCOMMERCE_AUTH_'.strtoupper($auth).'_DESCRIPTION',
                    'multiOptions' => $roles,
                    'value' => $roles_values
                ));
            }
        }
        else {
            $this->addElement('Radio', 'view', array(
                'label' => 'Allow Viewing Details of Listing?',
                'multiOptions' => array(
                    1 => 'Yes, allow users to view listings',
                    0 => 'No, do not allow users to view listings'
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