<?php

namespace App\Controller;

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

    public function __construct(MariaDBRepository $mariaDBRepository) // TODO:: Dodać repozytorium clickhouse
    {
        $this->mariaDBRepository = $mariaDBRepository;
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
                // TODO: Przekazać pobrane wiersze do Clickhouse'a
            }
        }

//        if ($data['from' === 'clickhouse']) {
//            $rows = []; // TODO:: Wyciągnąć dane z clickhouse
//            foreach ($rows as $row) {
//                 $this->mariaDBRepository->insert($row); // TODO:: Sprawdzić czy dodawanie działa
//            }
//        }

        return new JsonResponse([
            'rows' => $rows,
            'numberOfRows' => count($rows),
        ]);
    }
}
