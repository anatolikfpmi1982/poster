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
    public static function formatMoreText( $text ) {
        $strArray = explode( '<!--more-->', $text );
        if ( count( $strArray ) >= 2 ) {
            $text = '<div class="row">
                        <div class="col-md-12 col-sm-12">'
                    . $strArray[0] .
                    '<span id="more_text" onclick="$(\'#more_more\').show();$(\'#more_text\').remove();return false;"></span>' .
                    '</div>' .
                    '</div>' .
                    '<div class="row"  id="more_more" style="display:none;">
                        <div class="col-md-12 col-sm-12">
                        ' .
                    $strArray[1] .
                    '</div>' .
                    '</div>';
        }

        return $text;
    }
}