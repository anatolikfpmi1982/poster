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
            'frame_id' => $request->query->get('frame_id'),
            'frame_value' => $request->query->get('frame_value'),
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
        $this->get( 'app.session_manager' )->clearCart();

        // parameters to template
        return new JsonResponse(array('result' => 1));
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
