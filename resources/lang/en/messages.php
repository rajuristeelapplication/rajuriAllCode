<?php

return [


    'msg' => [
        'get' => [
            'success' => 'Get :module has been successfully',
            'error' => 'Get :module has been not found',
        ],
        'created' => [
            'success' => 'This :module has been created successfully',
            'error' => 'This :module has been not created, try again',
        ],
        'updated' => [
            'success' => 'This :module has been updated successfully',
            'error' => 'Get :module has been not updated, try again',
        ],
        'deleted' => [
            'success' => 'This :module has been deleted successfully',
            'error' => 'Get :module has been not deleted, try again',
        ],
        'status' => [
            'success' => 'This :module has been :type successfully',
            'error' => 'Get :module has been not :type, try again',
        ],
        'wrong' => [
            'success' => 'Your :module has been updated successfully',
            'error' => 'Your :module has been wrong, try again',
        ],
        'assign_already' => [
            'success' => 'This :module has been assigned, First remove it this then you can remove it this :module.',
            'error' => 'Get :module has been not :type, try again',
        ],
        'translate' => [
                'success' => ':module translation has been completed successfully',
                'error' => 'There is some problem to update :module translate'
        ],
        'not_found' => 'This :module has been not found',

        'contact_respond' => 'Admin respond email send successfully',
        'ticket_allocation' => 'Ticket allocation process successfully'
    ],




    'cms-contents' => [

        'create' => [
            'success' => 'cms has been created successfully',
            'error' => 'There is some problem to create cms',
        ],

        'update' => [
            'success' => 'cms has been updated successfully',
            'error' => 'There is some problem to update cms',
        ],

        'status' => [

            'success' => 'cms status has been changed successfully :status',
            'error' => 'There is some problem to update status',
        ],

        'delete' => [

            'success' => 'cms has been deleted successfully',

            'error' => 'There is some problem to delete cms',

        ],
        'translate' => [
            'update' => [
                'success' => 'cms Translation has been completed successfully',
                'error' => 'There is some problem to update cms translate'
            ],
        ],
        'not_found' => 'This cms has been not found',
    ],


    'languages' => [

        'create' => [
            'success' => 'Language has been created successfully',
            'error' => 'There is some problem to create language',
        ],

        'update' => [
            'success' => 'Language has been updated successfully',
            'error' => 'There is some problem to update language',
        ],

        'delete' => [
            'success' => 'Language has been deleted successfully',
            'error' => 'There is some problem to delete language',
        ],

        'status' => [
            'success' => 'Language status has been changed successfully :status',
            'error' => 'There is some problem to update status',
        ],

        'generate_lang' => [
            'success' => 'Language file has been generated successfully',
            'content_not_available' => 'Contents are not available for selected language',
        ],
        'not_allow' => "Note: You can't edit English language",

    ],

    'app_content' => [
        'import'=> [
            'success' => 'import content app successfully',
            'error' => 'content app not import, please try again',
        ],

        'create' => [
            'success' => 'App Contents has been created successfully',
            'error' => 'There is some problem to create app content',
        ],

        'update' => [
            'success' => 'App Contents has been updated successfully',
            'error' => 'There is some problem to update app content',
        ],
    ],

    'notification' => [

        'knowledge_status_title_admin' => 'Your Van request  has been <b>:status</b> by <b>:name</b>',
        'knowledge_status_content_admin' => 'Van Number :vehicleNumber',


        'knowledge_status_title' => '<b>:name</b> has requested knowledge center',
        'knowledge_status_content' => '<b>:name</b> has requested knowledge center',


        'leave_status_title_admin' => 'Your Leave request  has been <b>:status</b> by <b>:name</b>',
        'leave_status_content_admin' => 'Your Leave request  has been <b>:status</b> by <b>:name</b>',

        'leave_status_title' => '<b>:name</b> has requested  for  leave application',
        'leave_status_content' => '<b>:name</b> has requested  for  leave application',


        'order_status_title_admin' => 'Order has <b>:status</b>',
        // 'order_status_contain_admin' => '<b>:ft</b> has requested  for  leave application',

        'order_status_title' => '<b>:name</b> has requested  for  order',
        'order_status_content' => '<b>:name</b> has requested  for  order',



        'complain_status_title_admin' => 'Your Complain request  has been <b>:status</b> by <b>:name</b>',
        // 'complain_status_content_admin' => 'Van Number :vehicleNumber',

        'complain_status_title' => '<b>:name</b> has requested <b>:cname</b>',
        'complain_status_content' => '<b>:name</b> has requested <b>:cname</b>',

        'reimbursement_status_title_admin' => 'Your Reimbursement request  has been <b>:status</b> by <b>:name</b>',
        // 'complain_status_content_admin' => 'Van Number :vehicleNumber',

        'reimbursement_status_title' => '<b>:name</b> has requested Reimbursement',
        'reimbursement_status_content' => '<b>:name</b> has requested Reimbursement',
    ],

    'in-out.not_found' => 'Record Not Available'


];
