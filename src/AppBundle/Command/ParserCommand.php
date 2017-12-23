<?php

namespace AppBundle\Command;

use AppBundle\Entity\Author;
use AppBundle\Entity\Image;
use AppBundle\Entity\ParserError;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\ParserLog;
use AppBundle\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ZipArchive;

class ParserCommand extends ContainerAwareCommand
{
    const PARSE_DIR = __DIR__ . '/../../../web/uploads';
    const TMP_DIR = __DIR__ . '/../../../web/TMP';
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
    private $tmpFolder = null;

    /**
     * ParserCommand constructor.
     * @param null|string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->fs = new Filesystem();
//        $this->tmpFolder = sys_get_temp_dir() . '/' . self::TEMP_FOLDER_NAME;
        $this->tmpFolder = self::TMP_DIR;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('app:parser')
            ->setDescription('Parse pictures from CSV file.')
            ->setHelp('Parse pictures from CSV file.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->validateFiles();

        if($this->errors) {
            $this->saveErrors();
            return;
        }

        // create rows from CSV
        $rows = array();
        if (($handle = fopen(self::PARSE_DIR . '/' . self::CSV_FILENAME, "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, self::CSV_SEPARATOR)) !== FALSE) {
                $i++;
                if (self::CSV_IGNORE_FIRST_LINE && $i == 1) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        // validate CSV
        $files = $this->getFilenames();

        $this->validateCSV($rows, $files);

        if($this->errors) {
            $this->saveErrors();
            return;
        }

        // create pictures
        foreach($rows as $key => $row) {
            $filename = iconv('Windows-1251', 'Utf-8', $row[0]);
            $picture = $this->em->getRepository('AppBundle:Picture')->findOneBy(['name' => $filename]);
            if($picture) {
                if(!in_array($picture, $files))
                    continue;
            } else {
                $picture = new Picture();
            }
            $picture->setName(iconv('Windows-1251', 'Utf-8', $row[0]));
            $picture->setTitle(iconv('Windows-1251', 'Utf-8', $row[1]));

            $author = $this->em->getRepository('AppBundle:Author')->findOneBy(['name' => iconv('Windows-1251', 'Utf-8', $row[2])]);
            if(!$author) {
                $author = new Author();
                $author->setName(iconv('Windows-1251', 'Utf-8', $row[2]));
                $author->setIsActive(true);
                $this->em->persist($author);
                $this->em->flush();
            }
            $picture->setAuthor($author);
            $picture->setType((boolean)$row[4]);
            $picture->setNote(iconv('Windows-1251', 'Utf-8', $row[5]));
            $picture->setPrice($row[6] ? $row[6] : 0);
            $picture->setRatio($row[7] ? $row[7] : 1);
            $picture->setBody(iconv('Windows-1251', 'Utf-8', $row[8]));
            $picture->setIsActive(false);
            $picture->setIsTop(false);
            $picture->setCode(0);

            if(!empty($row[3])) {
                $categories = explode(',', iconv('Windows-1251', 'Utf-8', $row[3]));
                $categiriesEntities = $this->em->getRepository('AppBundle:Category3')->findBy(['title' => $categories]);
                $picture->setCategories($categiriesEntities);
            }

            $file = new UploadedFile($this->tmpFolder . '/' . $files[array_search($filename, $files)], $files[array_search($filename, $files)], null, null, null, true);
            $image = new Image();
            $image->setFile($file);
            $image->upload();
            $image->setCreatedAt(new \DateTime())
                ->setEntityName($picture::IMAGE_PATH);
            $this->em->persist($image);
            $picture->setImageBanner($image);

            $this->em->persist($picture);
        }

        $this->em->flush();

        $this->updateLog('Добавлено ' . count($rows) . ' картин.');

        $this->cleanFiles($files);

        $output->writeln('Success!');
    }

    /**
     * Validate
     */
    private function validateFiles()
    {
        if(!$this->fs->exists(self::PARSE_DIR)) {
            $this->errors[] = 'Папка загрузки недоступна!';
        }

        if(!$this->fs->exists(self::PARSE_DIR . '/' . self::ARCHIVE_FILENAME)) {
            $this->errors[] = 'Отсутствует архив с картинками!';
        } else {
            $this->prepareFiles();
        }

        if(!$this->fs->exists(self::PARSE_DIR . '/' . self::CSV_FILENAME)) {
            $this->errors[] = 'Отсутствует CSV файл!';
        }

        $lastDate = $this->em->getRepository('AppBundle:ParserLog')->findOneBy([], ['fileDate' => 'DESC']);

        if($lastDate && $lastDate->getFileDate()->format("F d Y H:i:s.") == date("F d Y H:i:s.", filectime(self::PARSE_DIR . '/' . self::CSV_FILENAME))) {
//            $this->errors[] = 'Файл CSV не был изменен!';
        }
    }

    /**
     *  Extract files to tmp folder from zip
     */
    private function prepareFiles() {
        $zip = new ZipArchive;
        if ($zip->open(self::PARSE_DIR . '/' . self::ARCHIVE_FILENAME) === TRUE) {
            $zip->extractTo($this->tmpFolder);
            $zip->close();
        } else {
            $this->errors[] = 'Невозможно распаковать архив!';
        }
    }

    private function validateCSV($rows, $files) {
        $categories = [];
        $categoriesEntities = $this->em->getRepository('AppBundle:Category3')->findBy([]);
        foreach ($categoriesEntities as $category) {
            $categories[] = $category->getTitle();
        }

        foreach ($rows as $key => $row) {
            $name = iconv('Windows-1251', 'Utf-8', $row[0]);
            $cat = iconv('Windows-1251', 'Utf-8', $row[3]);
            if(!in_array($name, $files)) {
                $this->errors[] = "Файл " . $name . ' отсутствует в архиве!';
            }

            $picture = $this->em->getRepository('AppBundle:Picture')->findOneBy(['name' => $name]);
            if($picture) {
                $this->errors[] = 'Файл ' . $name . ' уже присутствует в базе данных!';
            }

            if(!empty($row[3])) {
                $rowCategories = explode(',', $cat);

                foreach ($rowCategories as $category) {
                    if(!in_array($category, $categories)) {
                        $this->errors[] = 'Категория ' . $category . ' файла ' . $name . ' отсутствует в базе данных!';
                    }
                }
            }
        }

    }

    /**
     * Save new ParserLog Entity
     *
     * @param string $message
     */
    private function updateLog($message) {
        $entity = new ParserLog();
        $entity->setMessage($message);
        $entity->setFileDate(new \DateTime(date("F d Y H:i:s.", filectime(self::PARSE_DIR . '/' . self::CSV_FILENAME))));
        $entity->setUpdatedAt(new \DateTime());
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Save errors
     */
    private function saveErrors() {
        foreach($this->errors as $error) {
            $entity = new ParserError();
            $entity->setMessage($error);
            $entity->setCreatedAt(new \DateTime());
            $entity->setIsActive(false);
            $this->em->persist($entity);
        }

        $this->em->flush();
    }

    /**
     * Get array of filenames from extracted zip
     *
     * @return array
     */
    private function getFilenames() {
        $result = [];
        $files = scandir($this->tmpFolder);
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
            $file = $this->tmpFolder . '/' . $v;
            unset($file);
        }
    }
}