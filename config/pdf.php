<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => 'bay',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'bay',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('temp/'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    'font_path' => base_path('public/fonts/'),
    'font_data' => [
        'fa' => [
            'R'  => 'fonts/ttf/IRANSansWeb(FaNum).ttf',
            'B'  => 'fonts/ttf/IRANSansWeb(FaNum)_Bold.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
    ]
];
