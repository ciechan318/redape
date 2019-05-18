<?php

namespace App\Service;


class FlashManager
{

    //all flash types are defined again in templates/base.html.twig to reduce overhead; name MUST correspond with css class 'alert-TYPE'
    const FLASH_TYPE_SUCCESS = 'success';
    const FLASH_TYPE_WARNING = 'warning';
    const FLASH_TYPE_ERROR = 'error';
    const FLASH_MESSAGE_FORM_DATA_SAVED = 'flash_message_form_data_saved';

}
