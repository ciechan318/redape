<?php

namespace App\Service;


class LanguageManager
{

    /**
     * @var array
     */
    private $appLocales;

    public function __construct(array $appLocales)
    {
        $this->appLocales = $appLocales;
    }

    /**
     * @return string
     */
    public function getAppLocales(): array
    {
        return $this->appLocales;
    }

}
