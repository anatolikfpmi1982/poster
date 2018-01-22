<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Image;

class ImageFormIdentifier {
    const VERTICAL_FORM_NAME = 'rectangle vertical';
    const SQUARE_FORM_NAME = 'square';
    const HORISONTAL_FORM_NAME = 'horisontal';
    const HORISONTAL_LONG_FORM_NAME = 'horisontal_long';
    const VERTICAL_FORM_MAX_LIMIT = 0.86;
    const SQUARE_FORM_MIN_LIMIT = 0.86;
    const SQUARE_FORM_MAX_LIMIT = 1.12;
    const HORISONTAL_FORM_MIN_LIMIT = 1.12;
    const HORISONTAL_FORM_MAX_LIMIT = 1.7;
    const HORISONTAL_LONG_FORM_MIN_LIMIT = 1.7;

    /**
     * Function used to identify picture form.
     *
     * @param Image $image
     *
     * @return string form system name.
     */
    public static function identify(Image $image) {
        $form = null;
        $size = getimagesize($image->getOriginFile());
        $ratio = $size[0] / $size[1];

        if($ratio < self::VERTICAL_FORM_MAX_LIMIT) {
            $form = self::VERTICAL_FORM_NAME;
        } elseif($ratio >= self::SQUARE_FORM_MIN_LIMIT && $ratio < self::SQUARE_FORM_MAX_LIMIT) {
            $form = self::SQUARE_FORM_NAME;
        } elseif($ratio >= self::HORISONTAL_FORM_MIN_LIMIT && $ratio < self::HORISONTAL_FORM_MAX_LIMIT) {
            $form = self::HORISONTAL_FORM_NAME;
        } elseif($ratio >= self::HORISONTAL_LONG_FORM_MIN_LIMIT) {
            $form = self::HORISONTAL_LONG_FORM_NAME;
        }

        return $form;
    }
}