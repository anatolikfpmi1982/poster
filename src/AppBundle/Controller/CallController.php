<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Call;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CallController
 */
class CallController extends FrontController {
    /**
     * @Route("/ajax/call/add", name="call_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function callbackAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');

        $name = $request->query->get('fullname');
        $phone = $request->query->get('telephone');

        $call = new Call();
        $call->setName($name);
        $call->setPhone($phone);
        $call->setIsDone(false);
        $call->setCreatedAt(new \DateTime());
        $call->setUpdatedAt(new \DateTime());

        $em->persist($call);
        $em->flush();

        $settings = $this->getSiteSettings();

        if(!empty($settings['from_email']) && !empty('to_email')) {
            $message = (new \Swift_Message('Заказанный звонок'))
                ->setFrom($settings['from_email'])
                ->setTo($settings['to_email'])
                ->setBody(
                    $this->renderView(
                        'Emails/callback.html.twig',
                        array('name' => $name, 'phone' => $phone)
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);
        }

        // parameters to template
        return new JsonResponse(array('result' => 'success'));
    }
}
