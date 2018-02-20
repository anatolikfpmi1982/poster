<?php
namespace AppBundle\Controller;

use AppBundle\Entity\AuthorsPictures;
use Exception;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class AuthorsCRUDController extends BaseController
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

        $ids = [];
        $pictures = $object->getPictures();
        foreach($pictures as $k => $v) {
            $ids[] = $v->getId();
        }

        shuffle($ids);

        $authorsPictures = $object->getAuthorsPictures();
        foreach ($authorsPictures as $k => $v) {
            if(in_array($v->getPicture()->getId(), $ids) ) {
                $key = array_search($v->getPicture()->getId(), $ids);
                $v->setWeight( $key + 1);
                unset($ids[$key]);
            } else {
                $object->removeAuthorsPicture($v);
            }
        }

        foreach ($pictures as $k => $v) {
            if(in_array($v->getId(), $ids) ) {
                $key = array_search($v->getId(), $ids);

                $picture = new AuthorsPictures();
                $picture->setAuthor($object);
                $picture->setPicture($v);
                $picture->setWeight($key + 1);

                $object->addAuthorsPicture($picture);
            }
        }

        $this->admin->setSubject($object);
        $objectId = $this->admin->getNormalizedIdentifier($object);
        $existingObject = $this->admin->update($object);

        try {
            if ($this->isXmlHttpRequest()) {
                return $this->renderJson(array(
                    'result' => 'ok',
                    'objectId' => $objectId,
                    'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                ), 200, array());
            }

            $this->addFlash('sonata_flash_success', 'Рандомизация выполнена успешно!');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
        } catch (LockException $e) {
            $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', array(
                '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                '%link_start%' => '<a href="'.$this->admin->generateObjectUrl('edit', $existingObject).'">',
                '%link_end%' => '</a>',
            ), 'SonataAdminBundle'));
        }

        return new RedirectResponse($this->admin->generateUrl('list', ['filter' => $this->admin->getFilterParameters()]));
    }
}