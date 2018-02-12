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
            $text = '<div>' . (mb_strpos($strArray[0], '<p>', -3, 'UTF-8') !== null  ? mb_substr($strArray[0], 0, -3, 'UTF-8') : $strArray[0])
                . '</div>' . '<span id="more_text" onclick="$(\'#more_more\').show();$(\'#more_text\').remove(); ">...</span>'
                . '<div id="more_more" style="display:none;">'
                . (mb_strpos($strArray[1], '</p>', 0, 'UTF-8') === 0  ? mb_substr($strArray[1], 4, mb_strlen($strArray[1], 'UTF-8'), 'UTF-8') : $strArray[1])
                . '</div>';
        }

        return $text;
    }
}