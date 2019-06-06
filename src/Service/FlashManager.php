<?php

namespace App\Service;


class FlashManager
{

    //all type names MUST correspond with css class 'alert-TYPE'
    const FLASH_TYPE_SUCCESS = 'success';
    const FLASH_TYPE_WARNING = 'warning';
    const FLASH_TYPE_ERROR = 'error';
    const FLASH_MESSAGE_FORM_DATA_SAVED = 'flash_message_form_data_saved';
    const FLASH_MESSAGE_SEARCH_EMPTY = 'flash_message_search_empty';

}
