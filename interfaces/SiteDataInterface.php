<?php

namespace app\interfaces;

interface SiteDataInterface 
{
    //Возвращает данные для начального наполнения сайта
    public static function getBaseData($data);
}
