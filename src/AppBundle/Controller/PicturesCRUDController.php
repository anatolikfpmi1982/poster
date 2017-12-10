<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Picture;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PicturesCRUDController extends BaseController
{

    /**
     * List action.
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listAction()
    {
        $request = $this->getRequest();

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        $authorsChoices = [];
        $authors = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle\Entity\Author')->findBy(['isActive' => true]);
        if($authors) {
            foreach ($authors as $v) {
                $authorsChoices[$v->getId()] = (string)$v;
            }
        }
        $categoriesChoices = [];
        $categories = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle\Entity\Category3')->findBy(['isActive' => true]);
        if($categories) {
            foreach ($categories as $v) {
                $categoriesChoices[$v->getId()] = (string)$v;
            }
        }

        $actionForm = $this->get('form.factory')->createNamedBuilder('', 'form')
            ->add('a_price', 'text', array('required' => false, 'label' => 'tested at'))
            ->add('a_ratio', 'text', array('required' => false, 'label' => 'tested at'))
            ->add('a_author', 'choice', array('choices' => $authorsChoices, 'required' => false, 'label' => 'tested at'))
            ->add('a_art', 'choice', array('choices' => [1 => 'Арт', 0 => 'Фото'],'required' => false, 'label' => 'tested at'))
            ->add('a_show', 'choice', array('choices' => [1 => 'Да', 0 => 'Нет'], 'required' => false, 'label' => 'tested at'))
            ->add('a_top', 'choice', array('choices' => [1 => 'Да', 0 => 'Нет'], 'required' => false, 'label' => 'tested at'))
            ->add('a_title', 'text', ['required' => false, 'label' => 'Title'])
            ->add('a_description', 'text', ['required' => false, 'label' => 'Description'])
            ->add('a_note', 'text', ['required' => false, 'label' => 'Note'])
            ->add('a_category', 'choice', array('choices' => $categoriesChoices, 'required' => false, 'label' => 'tested at'))
            ->getForm();

        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action' => 'list',
            'form' => $formView,
            'datagrid' => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'actionForm' => $actionForm->createView(),
            'export_formats' => $this->has('sonata.admin.admin_exporter') ?
                $this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
                $this->admin->getExportFormats(),
        ), null);
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param FormView $formView
     * @param string   $theme
     */
    private function setFormTheme(FormView $formView, $theme)
    {
        $twig = $this->get('twig');

        try {
            $twig
                ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                ->setTheme($formView, $theme);
        } catch (\Twig_Error_Runtime $e) {
            // BC for Symfony < 3.2 where this runtime not exists
            $twig
                ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                ->renderer
                ->setTheme($formView, $theme);
        }
    }

    public function batchActionChangePrice(ProxyQueryInterface $query)
    {
        $price = $this->get('request')->request->get('a_price');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->setPrice($price);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменена цена');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeRatio(ProxyQueryInterface $query)
    {
        $ratio = $this->get('request')->request->get('a_ratio');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->setRatio($ratio);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменен коэффициент');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeAuthor(ProxyQueryInterface $query)
    {
        $author = (int)$this->get('request')->request->get('a_author');
        $authorEntity = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle\Entity\Author')->findOneBy(['id' => $author]);

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->setAuthor($authorEntity);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменен автор');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeArt(ProxyQueryInterface $query)
    {
        $art = (bool)$this->get('request')->request->get('a_art');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->setType($art);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменено арт/фото');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeShow(ProxyQueryInterface $query)
    {
        $show = (bool)$this->get('request')->request->get('a_show');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->setIsActive($show);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменено отображение картины.');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeTop(ProxyQueryInterface $query)
    {
        $top = (bool)$this->get('request')->request->get('a_top');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                /** @var Picture $entity */
                $entity->setIsTop($top);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно задали топы.');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionDeleteCategory(ProxyQueryInterface $query)
    {
        $category = (int)$this->get('request')->request->get('a_category');
        $categoryEntity = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle\Entity\Category3')->findOneBy(['id' => $category]);

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $entity->removeCategory($categoryEntity);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно удалена категория');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionAddCategory(ProxyQueryInterface $query)
    {
        $category = (int)$this->get('request')->request->get('a_category');
        $categoryEntity = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle\Entity\Category3')->findOneBy(['id' => $category]);

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                $update = true;
                foreach ($entity->getCategories() as $v) {
                    if($v->getId() == $categoryEntity->getId())
                        $update = false;
                }
                if($update) {
                    $entity->addCategory($categoryEntity);

                    // Model Manager missing flush() method.
                    $modelManager->update($entity);
                }

            }

            $this->addFlash('sonata_flash_success', 'Успешно добавлена категория');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeTitle(ProxyQueryInterface $query)
    {
        $title = $this->get('request')->request->get('a_title');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                /** @var Picture $entity */
                $entity->setTitle($title);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменено название.');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeDescription(ProxyQueryInterface $query)
    {
        $description = $this->get('request')->request->get('a_description');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                /** @var Picture $entity */
                $entity->setBody($description);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменено описание.');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

    public function batchActionChangeNote(ProxyQueryInterface $query)
    {
        $note = $this->get('request')->request->get('a_note');

        $modelManager = $this->admin->getModelManager();
        try {
            foreach ($query->execute() as $entity) {
                /** @var Picture $entity */
                $entity->setNote($note);

                // Model Manager missing flush() method.
                $modelManager->update($entity);
            }

            $this->addFlash('sonata_flash_success', 'Успешно изменено примечание.');
        } catch (ModelManagerException $e) {
            $this->handleModelManagerException($e);
            $this->addFlash('sonata_flash_error', 'flash_batch_delete_error');
        }

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }
}