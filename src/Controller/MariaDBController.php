<?php

namespace App\Controller;

use App\Repository\MariaDBRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MariaDBController extends AbstractController
{
    /** @var MariaDBRepository */
    private $mariaDBRepository;

    public function __construct(MariaDBRepository $mariaDBRepository)
    {
        $this->mariaDBRepository = $mariaDBRepository;
    }

    /**
     * @Route("/mariadb/{page}", name="mariadb",  defaults={"page": 1})
     */
    public function index(Request $request): Response
    {
        $page = $request->get('page');
        $dataSet = $this->mariaDBRepository->fetchAll($page);

        return $this->render('mariadb/index.html.twig', [
            'rows' => $dataSet,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/mariadb/edit/{id}", name="loadMariaDBEditView", methods={"GET"})
     */
    public function loadEditView(Request $request): Response
    {
        $id = $request->get('id');
        $arr = $this->mariaDBRepository->fetch($id);

        return $this->render('mariadb/edit.html.twig', [
            'id' => $arr['id'],
            'name' => $arr['Name'],
            'age' => $arr['Age'],
            'city' => $arr['City'],
            'page' => $request->get('page'),
        ]);
    }

    /**
     * @Route("/mariadb/edit/{id}", name="mariaDBEditRow", methods={"POST"})
     */
    public function editData(Request $request): Response
    {
        $page = $request->get('page');
        $this->mariaDBRepository->edit($request->request->all());

        $this->addFlash('success', 'Data Changed successfully');

        return $this->redirectToRoute('mariadb', ['page' => $page]);
    }

    /**
     * @Route("/mariaDBDeleteRow/{id}", name="mariaDBDeleteRow", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $page = $request->get('page');
        $this->mariaDBRepository->delete($id);

        $this->addFlash('success', 'Row Removed successfully');

        return $this->redirectToRoute('mariadb', ['page' => $page]);
    }
}
