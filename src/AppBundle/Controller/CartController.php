<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 */
class CartController extends FrontController {
    /**
     * @Route("/ajax/cart/add", name="cart_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addToCartAction(Request $request) {
        $data = [
            'id' => $request->query->get('cart_id') ? $request->query->get('cart_id') : uniqid('cart_id', false),
            'own_picture_id' => $request->query->get('own_picture_id'),
            'picture_id' => $request->query->get('id'),
            'price' => $request->query->get('price'),
            'sizes' => $request->query->get('sizes'),
            'isOwnSize' => $request->query->get('isOwnSize'),
            'banner_material_id' => $request->query->get('banner_material_id'),
            'banner_material_value' => $request->query->get('banner_material_value'),
            'underframe_id' => $request->query->get('underframe_id'),
            'underframe_value' => $request->query->get('underframe_value'),
            'frame_material_id' => $request->query->get('frame_material_id'),
            'frame_material_value' => $request->query->get('frame_material_value'),
            'frame_material_corner' => $request->query->get('frame_material_corner'),
            'frame_material_side_t' => $request->query->get('frame_material_side_t'),
            'frame_material_side_r' => $request->query->get('frame_material_side_r'),
            'frame_material_side_b' => $request->query->get('frame_material_side_b'),
            'frame_material_side_l' => $request->query->get('frame_material_side_l'),
            'frame_id' => $request->query->get('frame_id'),
            'frame_value' => $request->query->get('frame_value'),
            'frame_color' => $request->query->get('frame_color'),
            'module_type_id' => $request->query->get('module_type_id'),
            'module_type_value' => $request->query->get('module_type_value'),
            'type_id' => $request->query->get('type_id'),
            'type_value' => $request->query->get('type_value'),
            'module_formula' => $request->query->get('module_formula'),
            'module_size' => $request->query->get('module_size'),
        ];

        $this->get( 'app.session_manager' )->addToCart( $data );

        // parameters to template
        return new JsonResponse(array('result' => 'success'));
    }

    /**
     * @Route("/ajax/cart/delete", name="cart_delete")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteFromCartAction(Request $request) {
        $id = $request->query->get('id');

        $this->get( 'app.session_manager' )->deleteFromCart( $id );

        // parameters to template
        return new JsonResponse(array('result' => 1));
    }

    /**
     * @Route("/ajax/cart/clear", name="cart_clear")
     *
     * @return Response
     */
    public function clearCartAction() {
        $this->get( 'app.session_manager' )->cleanCart();
        $count = $this->get( 'app.session_manager' )->getCartCount();

        // parameters to template
        return new JsonResponse(array('result' => 1, 'count' => $count));
    }

    /**
     * @Route("/ajax/cart/count", name="cart_count")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getCartCountAction(Request $request) {
        $count = $this->get( 'app.session_manager' )->getCartCount();

        // parameters to template
        return new JsonResponse(array('result' => 'success', 'count' => $count));
    }
}
