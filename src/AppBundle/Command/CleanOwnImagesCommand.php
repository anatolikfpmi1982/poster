<?php

namespace AppBundle\Command;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CleanOwnImagesCommand extends ContainerAwareCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:clean-own-pictures')
            ->setDescription('Clean own pictures without orders.')
            ->setHelp('Clean own pictures without orders.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('AppBundle:Order', 'o');
        $rsm->addFieldResult('o', 'id', 'id');
        // build rsm here

        $query = $this->em->createNativeQuery('SELECT DISTINCT own_picture_id as id FROM orders', $rsm);
        $ownPictures = $query->getResult();
        $ids = null;
        if(count($ownPictures) > 0 && !empty($ownPictures[0])) {
            foreach($ownPictures as $v) {
                $ids[] = $v->getOwnPicture()->getId();
            }
        }

        $ownPictures = $this->em->getRepository('AppBundle:OwnPicture')->getOwnPicturesForClean($ids);

        if(count($ownPictures) > 0) {
            $this->deleteOwnPictures($ownPictures);
        }

        $output->writeln('Success!');
    }

    /**
     * Delete own pictures by ORM.
     * @param $pictures
     */
    public function deleteOwnPictures($pictures){
        if (count($pictures) > 0) {
            foreach($pictures as $picture) {
                $image = $picture->getImage();
                $image->onPreRemove();
                $this->em->remove($image);
                /** @var OwnPicture $picture  */
                $this->em->remove($picture);
            }
            $this->em->flush();
        }
    }
}