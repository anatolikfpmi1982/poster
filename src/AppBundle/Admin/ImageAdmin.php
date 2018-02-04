<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ImageAdmin extends AbstractAdmin
{
    protected static $imageIndex = 0;

    protected function configureFormFields(FormMapper $formMapper)
    {
        if($this->hasParentFieldDescription()) { // this Admin is embedded
            $getter = 'get' . $this->getParentFieldDescription()->getFieldName();
            $parent = $this->getParentFieldDescription()->getAdmin()->getSubject();
            $subject = null;
            if ($parent) {
                $subject = $parent->$getter();
            }
        } else {
            $subject = $this->getSubject();
        }

        if ($subject instanceof \Doctrine\ORM\PersistentCollection) {
            $subject = $subject->toArray();
        }

        $fileFieldOptions = array('required' => false);

        if (is_array($subject)) {
            if (is_array($subject) && array_key_exists(self::$imageIndex, $subject)) {
                $img = $subject[self::$imageIndex];
                self::$imageIndex++;
            } else {
                $img = null;
            }
            if($img !== null) {
                /** @var Image $img */
                $fileFieldOptions['help'] = '<img src="/files/'.$img->getEntityName().'/small_thumb/'.$img->getFilename().'" class="admin-preview" />';
            }

            switch (self::$imageIndex) {
                case 1:
                    $fileFieldOptions['help'] .= '<b>Основное изображение</b>';
                    break;
                case 2:
                    $fileFieldOptions['help'] .= '<b>Угол - изображение</b>';
                    break;
                case 3:
                    $fileFieldOptions['help'] .= '<b>Верхняя полоса - изображение</b>';
                    break;
                case 4:
                    $fileFieldOptions['help'] .= '<b>Правая полоса - изображение</b>';
                    break;
                case 5:
                    $fileFieldOptions['help'] .= '<b>Нижняя полоса - изображение</b>';
                    break;
                case 6:
                    $fileFieldOptions['help'] .= '<b>Левая полоса - изображение</b>';
                    break;
            }
        } elseif ($subject instanceof Image) {
            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['help'] = '<img src="/files/'.$subject->getEntityName().'/small_thumb/'.$subject->getFilename().'" class="admin-preview" /><br>';
        }
        $formMapper
            ->add('file',       'file',     $fileFieldOptions);

    }

    public function prePersist($image)
    {
        $this->manageFileUpload($image);
    }

    public function preUpdate($image)
    {
        $this->manageFileUpload($image);
    }

    private function manageFileUpload($image)
    {
        if ($image instanceof Image && $image->getFile()) {
            $image->refreshUpdated();
        }
    }
}