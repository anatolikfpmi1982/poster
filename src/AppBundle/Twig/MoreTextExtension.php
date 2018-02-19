<?php
namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class MoreTextExtension extends AbstractExtension {
    public function getFilters()
    {
        return array(
            new TwigFilter('moreText', array($this, 'moreTextFilter')),
        );
    }

    public function moreTextFilter($text)
    {
        $strArray = explode( '<!--more-->', $text );
        if ( count( $strArray ) >= 2 ) {
            $text = '<div class="row">
                        <div class="col-md-12 col-sm-12">'
                . $strArray[0] .
                '<span class="more_text" onclick="$(this).parents().eq(3).find(\'.more_more\').show();$(this).remove();return false;"></span>' .
                '</div>' .
                '</div>' .
                '<div class="row more_more" style="display:none;">
                        <div class="col-md-12 col-sm-12">
                        ' .
                $strArray[1] .
                '</div>' .
                '</div>';
        }

        return $text;
    }

}