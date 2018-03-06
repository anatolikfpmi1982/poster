<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ParserController
 */
class ParserController extends Controller
{
    /**
     * @Route("/parser", name="parser")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showAction()
    {
        $process = new Process('nohup php ' . $this->get('kernel')->getRootDir() . '/console app:parser');
        $process->run();

        return new Response("Парсер начал работу! Проверьте результаты работы через несколько минут!");
    }

}
