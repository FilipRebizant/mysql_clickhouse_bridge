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

    public function __construct(MariaDBRepository $mariaDBRepository)
    {
        $this->mariaDBRepository = $mariaDBRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
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
            $result = $this->mariaDBRepository->getDataFromTables($data['columns'], $data['counter']);

//            $this->mariaDBRepository->
        }

        // TODO: Przekazać pobrane wiersze do Clickhouse'a

        return new JsonResponse([
            'rows' => $result,
//            'numberOfRows' => count($result),
        ]);
    }
}
