<?php

namespace App\Controller;

use App\Repository\ClickhouseRepository;
use App\Repository\MariaDBRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /** @var MariaDBRepository  */
    private $mariaDBRepository;

    /** @var ClickhouseRepository */
    private $clickhouseRepository;

    public function __construct(
        MariaDBRepository $mariaDBRepository,
        ClickhouseRepository $clickhouseRepository
    ) {
        $this->mariaDBRepository = $mariaDBRepository;
        $this->clickhouseRepository = $clickhouseRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $columns = $this->mariaDBRepository->getColumnNames();

        return $this->render('home/index.html.twig', [
            'columns' => $columns,
        ]);
    }

    /**
     * @Route("/copyData", name="copyData", methods={"POST"})
     */
    public function copyData(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data['from'] === 'mariaDB') {
            $rows = $this->mariaDBRepository->getDataFromTables($data['columns'], $data['counter']);

            foreach ($rows as $row) {
                if ($row['id']) {
                    $row['id'] = (int) $row['id'];
                }
                $this->clickhouseRepository->insert($row);
            }
        }

        if ($data['from' === 'clickhouse']) {
            $rows = [];
//            var_dump($rows);
//            die;
//            foreach ($rows as $row) {
//                 $this->mariaDBRepository->insert($row); // TODO:: Sprawdzić czy dodawanie działa
//            }
        }

        return new JsonResponse([
            'rows' => $rows,
            'numberOfRows' => count($rows),
        ]);
    }
}
