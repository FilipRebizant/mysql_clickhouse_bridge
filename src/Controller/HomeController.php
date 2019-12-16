<?php

namespace App\Controller;

use App\Repository\ClickHouseRepository;
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

    /** @var ClickHouseRepository */
    private $clickHouseRepository;

    public function __construct(
        MariaDBRepository $mariaDBRepository,
        ClickHouseRepository $clickHouseRepository
    ) {
        $this->mariaDBRepository = $mariaDBRepository;
        $this->clickHouseRepository = $clickHouseRepository;
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
                if (array_key_exists('id', $row)) {
                    $row['id'] = (int) $row['id'];
                }

                if (array_key_exists('Age', $row)) {
                    $row['Age'] = (int) $row['Age'];
                }

                $this->clickHouseRepository->insert($row);
            }
        }

        if ($data['from'] === 'clickHouse') {
            $rows = $this->clickHouseRepository->getDataFromTables($data['columns'], $data['counter']);

            foreach ($rows as $row) {
                $this->mariaDBRepository->insert($row);
            }
        }

        return new JsonResponse([
            'rows' => $rows,
            'numberOfRows' => count($rows),
        ]);
    }
}
