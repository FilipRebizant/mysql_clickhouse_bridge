<?php

namespace App\Controller;

use App\Repository\ClickHouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClickhouseController extends AbstractController
{
    private $clickhouseRepository;

    public function __construct(ClickHouseRepository $clickhouseRepository)
    {
        $this->clickhouseRepository = $clickhouseRepository;
    }

    /**
     * @Route("/clickhouse/{page}", name="clickhouse",  defaults={"page": 1})
     */
    public function index(Request $request): Response
    {
        $page = $request->get('page');
        $dataSet = $this->clickhouseRepository->fetchAll($page);

        return $this->render('clickhouse/index.html.twig', [
            'rows' => $dataSet,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/clickhouse/edit/{id}", name="clickhouseEditView", methods={"GET"})
     */
    public function loadEditView(Request $request): Response
    {
        $id = $request->get('id');
        $arr = $this->clickhouseRepository->fetch($id);

        return $this->render('clickhouse/edit.html.twig', [
            'id' => $arr['id'],
            'name' => $arr['Name'],
            'age' => $arr['Age'],
            'city' => $arr['City'],
            'page' => $request->get('page'),
        ]);
    }

    /**
     * @Route("/clickhouse/edit/{id}", name="clickhouseEditRow", methods={"POST"})
     */
    public function editData(Request $request): Response
    {
        $page = $request->get('page');
        $this->clickhouseRepository->edit($request->request->all());

        $this->addFlash('success', 'Data Changed successfully');

        return $this->redirectToRoute('clickhouse', ['page' => $page]);
    }

    /**
     * @Route("/clickhouseDeleteRow/{id}", name="clickhouseDeleteRow", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $page = $request->get('page');
        $this->clickhouseRepository->delete($id);

        $this->addFlash('success', 'Row Removed successfully');

        return $this->redirectToRoute('clickhouse', ['page' => $page]);
    }

    /**
     * @Route("/clickhouse_delete_all/", name="clickhouseDeleteAll", methods={"POST"})
     */
    public function deleteAll(Request $request): Response
    {
        $this->clickhouseRepository->deleteAll();

        $this->addFlash('success', 'Database cleared successfully');

        return $this->redirectToRoute('clickhouse', ['page' => 1]);
    }

    /**
     * @Route("/clickHouse_number_of_rows/", name="clickhouseShowNumberOfRows", methods={"POST"})
     */
    public function showRowsNumber(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $rows = $this->clickhouseRepository->getRowsCount($data['columns']);

        return new JsonResponse([
            'result' => $rows
        ], 200);
    }

    /**
     * @Route("/clickhouse_setup_database/", name="clickhouseSetupDatabase", methods={"GET"})
     */
    public function setupDatabase()
    {
        $this->clickhouseRepository->setupDatabase();

        return new Response('Setup ok, database was set');
    }
}
