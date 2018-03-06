<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

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
        $process = new Process('php app/console app:parser');
        $process->start();

        return new Response("Парсер начал работу! Проверьте результаты работы через несколько минут!");
    }

}
