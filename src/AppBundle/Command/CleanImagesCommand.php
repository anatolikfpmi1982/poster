<?php

namespace AppBundle\Command;

use AppBundle\Entity\Author;
use AppBundle\Entity\Image;
use AppBundle\Entity\ParserError;
use Oro\ORM\Query\AST\Platform\Functions\Mysql\Date;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\ParserLog;
use AppBundle\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ZipArchive;

class CleanImagesCommand extends ContainerAwareCommand
{
    const PARSE_DIR = __DIR__ . '/../../../web/uploads';
    const TMP_DIR = __DIR__ . '/../../../web/TMP';
    const PICTURES_DIR = __DIR__ . '/../../../web/files/pictures';
    const ARCHIVE_FILENAME = 'archive.zip';
    const CSV_FILENAME = 'upload.csv';
    const TEMP_FOLDER_NAME = 'TMP';
    const CSV_SEPARATOR = ';';
    const CSV_IGNORE_FIRST_LINE = true;

    /**
     * @var null|Filesystem
     */
    private $fs = null;

    /**
     * @var null
     */
    private $errors = null;

    /**
     * @var null|string
     */
    private $folder = null;

    /**
     * ParserCommand constructor.
     * @param null|string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->fs = new Filesystem();
        $this->folder = self::PICTURES_DIR;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:cleanimages')
            ->setDescription('Clean images.')
            ->setHelp('Clean images.')
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
        $this->getContainer()->get('app.image_manager')->cleanGarbageImages();

        $files = $this->getFilenames();
        unset($files[array_search('thumb', $files)]);
        unset($files[array_search('mini_thumb', $files)]);
        unset($files[array_search('small_thumb', $files)]);
        unset($files[array_search('normal', $files)]);
        unset($files[array_search('.DS_Store', $files)]);
        $images = $this->getEm()->getRepository('AppBundle:Image')->findBy([]);
        foreach($images as $v) {
            if(in_array($v->getFilename(), $files)) {
                unset($files[array_search($v->getFilename(), $files)]);
            }

        }

        $this->cleanFiles($files);

        $output->writeln('Success!');
    }

    /**
     * Get array of filenames from folder
     *
     * @return array
     */
    private function getFilenames() {
        $result = [];
        $files = scandir($this->folder);
        foreach ($files as $v) {
            if($v != '.' && $v != '..' && '__MACOSX') {
                $result[] = $v;
            }
        }

        return $result;
    }

    /**
     * Delete files from extracted zip
     *
     * @param array $files
     * @return void
     */
    private function cleanFiles($files) {
        foreach ($files as $v) {
            $file = $this->folder . '/' . $v;
            if(file_exists($file)) {
                unlink($file);
            }
            $file = $this->folder . '/mini_thumb/' . $v;
            if(file_exists($file)) {
                unlink($file);
            }
            $file = $this->folder . '/small_thumb/' . $v;
            if(file_exists($file)) {
                unlink($file);
            }
            $file = $this->folder . '/normal/' . $v;
            if(file_exists($file)) {
                unlink($file);
            }
            $file = $this->folder . '/thumb/' . $v;
            if(file_exists($file)) {
                unlink($file);
            }
        }
    }

    public function getEm() {
        if ($this->em->getConnection()->ping() === false) {
            $this->em->getConnection()->close();
            $this->em->getConnection()->connect();
        }

        return $this->em;
    }
}