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
        $text = str_replace('<!--more-open-->', "<span class='more_open' onclick='$(this).next().show();$(this).next().next().css(\"display\", \"inline-block\");$(this).hide();return false;'></span><div class='more_block'>", $text);
        $text = str_replace('<!--more-close-->', "</div><span class='more_close' onclick='$(this).prev().hide();$(this).prev().prev().css(\"display\", \"inline-block\");$(this).hide();return false;'></span>", $text);

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