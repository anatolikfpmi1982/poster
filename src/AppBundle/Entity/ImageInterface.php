<?php
namespace AppBundle\Entity;

interface ImageInterface {
    /**
     * Thumb image height
     */
    const THUMB_IMAGE_HEIGHT = 273;

    /**
     * Thumb image width
     */
    const THUMB_IMAGE_WIDTH = 365;

    /**
     * Thumb image height
     */
    const IMAGE_HEIGHT = 500;

    /**
     * Thumb image width
     */
    const IMAGE_WIDTH = 500;

    /**
     * Mini thumb image height
     */
    const THUMB_MINI_IMAGE_HEIGHT = 143;

    /**
     * Mini thumb image width
     */
    const THUMB_MINI_IMAGE_WIDTH = 150;

    /**
     * Mini thumb image height
     */
    const THUMB_MAX_IMAGE_HEIGHT = 600;

    /**
     * Mini thumb image width
     */
    const THUMB_MAX_IMAGE_WIDTH = 800;

    /**
     * Small thumb image width
     */
    const THUMB_SMALL_IMAGE_HEIGHT = 80;

    /**
     * Small thumb image width
     */
    const THUMB_SMALL_IMAGE_WIDTH = 60;
}