<?php
return [
    'admins' => array_filter(explode(',', env('ADMINS', 'marvy@gmail.com')),
        fn($item) => $item !== ''
    ),
    'max_page_to_show' => env('MAX_PAGE_TO_SHOW', 10)
];
