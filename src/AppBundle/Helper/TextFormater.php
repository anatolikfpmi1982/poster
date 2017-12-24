<?php

namespace AppBundle\Helper;

class TextFormater {

    /**
     * Function used to replace CKEditor "More".
     *
     * @param string $text
     *
     * @return string form system name.
     */
    public static function formatMoreText($text) {
        $strArray =  explode('<!--more-->', $text);
        if(count($strArray) >= 2) {
            $text = $strArray[0] . '</p>'
                . '<a id="more_text" onclick="$(\'#more_more\').show();$(\'#more_text\').remove(); ">Подробнее...</a>'
                . '<div id="more_more" style="display:none;"><p>' . $strArray[1] . '</div>';
        }

        return $text;
    }
}