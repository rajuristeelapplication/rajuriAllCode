<?php
return [


    // 'baseUrlS3' => 'http://localhost/rajuri-steel/public/storage/',
    'baseUrlS3' => 'http://14.99.147.156:8888/rajuri-steel/public/storage/',
    'S3Local' => 'public', // s3,public
    'dealer_image' => 'images/dealer',
    'profile_image' => 'images/profile',
    'photo_id_image' => 'images/photoid',
    'in_out_image' => 'images/inout',
    'schedule_image' => 'images/schedule',
    'knowledge_image' => 'images/knowledge',
    'complaint_image' => 'images/complaint',
    'reimbursement_image' => 'images/reimbursement',
    'voice_recording_image' => 'images/voice_recording',
    'merchandises_image' => 'images/merchandises',
    'brochures_image' => 'images/brochures',
    'company_sliders' => 'images/company_sliders',
    'pay_slip' => 'images/pay_slip',

    'sandbox_otp' => env('SANDBOX_OTP', 'TRUE'),
    'admin_id' => '53a645a6-5e57-11ec-969a-000c293f1073',
    'sales_executive_id' => '53a645a6-5e57-11ec-969a-000c293f1074',
    'marketing_executive_id' => '67658023-88a4-11ec-879c-000c293f1073',
    'notifications' => '1645acbb-9aaf-11ec-879c-000c293f1073',
    'ma_id' => 'af2c1466-9abc-11ec-879c-000c293f1073',
    'hr_id' => '1645acbb-9aaf-11ec-879c-000c293f1073',
    'IOSAppVersion' => '1.0',
    'AndroidAppVersion' => '1',
    'statePagination' => 10,
    'cityPagination' => 10,
    'talukaPagination' => 10,
    'regionsPagination' => 10,
    'dealerPagination' => 10,
    'schedulePagination' => 10,
    'leadPagination' => 10,
    'paySlipPagination' => 10,
    'reimbursementHistoryPagination' => 10,
    'followUpPagination' => 10,
    'complainPagination' => 10,
    'knowledgePagination' => 10,
    'leavePagination' =>10,
    'voiceRecordingPagination' =>10,
    'productPagination' => 10,
    'merchandisesPagination' =>10,
    'dealerRadius' => 200,
    'dob_format' => '%e %b,%Y',
    'admin_dob_format' => 'd M,Y',
    'admin_dealer_time_format' => 'h:i',
    'admin_dealer_time_format_create' => 'h:i A',
    'schedule_date_format' => '%e %b,%Y',
    'schedule_time_format' => '%h:%i %p',
    'rupess_symbol' => '₹',
    // 'trackTimer'=>60,
    // 'gpsRadius'=>100.0,
    'trackTimer'=>30,
    'gpsRadius'=>-1.0,
    'purposeArray'=> ['Daily Visit','Counter Visit','Mason Meet Discussion','Payment Follow-up','Material Inquiry','Birthday Celebration','Gift Distribution','Dealership Inquiry','Knowledge Centre Van Visit','Complaint','Other'],

    'vehicleArray'=> ['MH 23 E 8592','MH 21 X 5724','MH 21 X 5722','MH 23 W 0711'],


    'schedule_date_time_format' => '%e %b,%Y %h:%i %p',

    'holiday_first_day' => '%D',
    'holiday_month' => '%b',
    'holiday_day' => '%W',
    'payslip_month_year' => '%M-%y',
    'payslip_month_year_admin' => '%M %Y',

    'request_payslip_month_year' => '%M - %Y',


    'in_out_date_time_format' => '%e %b,%Y %h:%i %p',

    'notification_date_time' => '%e %b,%Y | %h:%i %p',

    // H:i
    'dealer_time_format' => '%h:%i',

    // follow up Y-m-d H:i
    'follow_up_time_format' => '%Y-%m-%e %h:%i',

    'report_date_format' => 'd M,Y g:i A ',


    'APIKEY' => 'AIzaSyC8qMF-PkOwchr848q6-1xBX3VVFzUyJaM',
    'PROJECTID' => 'rajuri-steel-346507',

    //  'push_notification' => [
    //     'url' => 'https://fcm.googleapis.com/fcm/send',
    //     'token' => 'AAAAE8XwNoU:APA91bEqLP2WBSH17VaQlqSA-sr7yewBUSxgGKqEZO-94Q3U_sHi4ty4xg1OopacL9_l6Zo5gT3R50jgbfbzbdCvJB_TQPDqhpc5L6H3yGCR99ThMJ737MrW7tKVqqtYFuWsxrpPvREN',
    //     'project_id' => 'rajuri-steel'
    // ],


    'push_notification' => [
        'url' => 'https://fcm.googleapis.com/fcm/send',
        'token' => 'AAAAtEhDujw:APA91bHpua7u5KKk9JtUjG3YCF-RZoOuU-lMRzz7zAM5a9BY3SdSaZUbORUwVUNKuySf2gv3bzM8eusF56jzSRCEVZumcVppHvUK2DC1cal8ZnzxcvqHMMZUATTjExaVHT9Xh2pbLpBF',
        'project_id' => 'rajuri-steel-346507'
    ],

    // Birthday Wish Message
    'birthday_wish_message' => "“It seems such a great day to say we feel so lucky that you came our way! Happy Birthday to you! Make it grand!” From - Rajuri Steel",
];
