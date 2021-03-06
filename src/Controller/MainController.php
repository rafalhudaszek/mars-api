<?php

namespace App\Controller;

use App\Entity\Measurement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;

class MainController extends AbstractController
{

    /**
     * @Route("/{id?}", name="init")
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function init(Request $request)
    {
        $session = $this->get("session");

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://api.nasa.gov/insight_weather/?api_key=zpj8nG5YUcakf6K1RihjAd75zX8QJmgceeqx2B4f&feedtype=json&ver=1.0');
        $statusCode = $response->getStatusCode();
        if($statusCode != 200)
        {
            print_r("erorrr");
            return $this->json([
                'message' => 'Welcome to your new controller!',
                'path' => 'src/Controller/MainController.php',
            ]);
        }
        else
        {
            $table = $response->toArray();
            $measurementTable = [];
            for ($i = 0; $i < count($table['sol_keys']); $i++)
            {
                $measurement = new Measurement();
                array_push($measurementTable, $measurement);

                $measurement->setId(intval($table['sol_keys'][$i]));
                if(self::isValid($table, intval($table['sol_keys'][$i])))
                {
                    $measurement->setDate(preg_replace("/[^0-9-:]/", " ", $table[$measurement->getId()]['First_UTC']));

                    $measurement->setTmax($table[$measurement->getId()]['AT']['mx']);
                    $measurement->setTavg($table[$measurement->getId()]['AT']['av']);
                    $measurement->setTmin($table[$measurement->getId()]['AT']['mn']);

                    $measurement->setPavg($table[$measurement->getId()]['PRE']['av']);

                    $measurement->setWind($table[$measurement->getId()]['HWS']['av']);
                }
                else
                {
                    $measurement->setDate('Pomiar jest nie ważny');
                }
            }

            $session->set('table', $measurementTable);

            $id = $request->get('id') - $measurementTable[0]->getId();
            return $this->render('Main/index.html.twig',[
                'table' => $measurementTable,

            ]);
        }
    }

    public function isValid(array $table, int $measurementId)
    {
        $valid = true;
        if(! $table['validity_checks'][$measurementId]['AT']['valid'])
        {
            $valid = false;
        }
        elseif(! $table['validity_checks'][$measurementId]['HWS']['valid'])
        {
            $valid = false;
        }
        elseif(! $table['validity_checks'][$measurementId]['PRE']['valid'])
        {
            $valid = false;
        }
        return $valid;
    }

    /**
     * @Route("/pomiar/{id?}", name="pomiar")
     * @param Request $request
     * @return Response
     */
    public function display(Request $request)
    {
        $session = $this->get('session');
        $measurementTable = $session->get('table');
        $id = $request->get('id');
        $renderFlag = true;

        for($i = 0; $i < count($measurementTable); $i++)
        {
            if($measurementTable[$i]->getId() == $id)
            {
                $id = $i;
            }
        }

        return $this->render('Main/display.html.twig',[
            'table' => $measurementTable,
            'id' => $id,
            'renderFlag' => $renderFlag
        ]);
    }


}
