<?php
namespace AppBundle\Controller;

use Exception;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class CategoriesCRUDController extends BaseController
{
    /**
     * Generate action.
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     * @throws Exception
     */
    public function generateAction($id)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->admin->checkAccess('edit', $object);

//        $preResponse = $this->preDelete($request, $object);
//        if ($preResponse !== null) {
//            return $preResponse;
//        }

//        if ($this->getRestMethod() == 'POST') {
//            // check the csrf token
//            $this->validateCsrfToken('sonata.edit');
//
//            $objectName = $this->admin->toString($object);
//
//            try {
//                $this->admin->delete($object);
//
//                if ($this->isXmlHttpRequest()) {
//                    return $this->renderJson(array('result' => 'ok'), 200, array());
//                }
//
//                $this->addFlash(
//                    'sonata_flash_success',
//                    $this->trans(
//                        'flash_delete_success',
//                        array('%name%' => $this->escapeHtml($objectName)),
//                        'SonataAdminBundle'
//                    )
//                );
//            } catch (ModelManagerException $e) {
//                $this->handleModelManagerException($e);
//
//                if ($this->isXmlHttpRequest()) {
//                    return $this->renderJson(array('result' => 'error'), 200, array());
//                }
//
//                $this->addFlash(
//                    'sonata_flash_error',
//                    $this->trans(
//                        'flash_delete_error',
//                        array('%name%' => $this->escapeHtml($objectName)),
//                        'SonataAdminBundle'
//                    )
//                );
//            }
//
//            return $this->redirectTo($object);
//        }

        $this->addFlash('sonata_flash_success', 'Рандомизация выполнена успешно!');

        return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
    }
}