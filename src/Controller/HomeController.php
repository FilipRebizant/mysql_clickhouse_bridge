<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /** @var Connection  */
    private $clickHouseConnection;

    /** @var Connection */
    private $mariaDBConnection;

    const QUERY_LIMIT = 50;

    public function __construct(ContainerInterface $container)
    {
        $this->clickHouseConnection = $container->get('doctrine.dbal.clickhouse_connection');
        $this->mariaDBConnection = $container->get('doctrine.dbal.mariadb_connection');
        $this->paginator = $container->get('knp_paginator');
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/clickhouse/{page}", name="clickhouse", defaults={"page": 1})
     */
    public function getDataFromClickHouse(Request $request)
    {
        $page = $request->get('page');
        // TODO:: Implement this method
//        $dataSet = $this->clickHouseConnection->fetchAll("SELECT * FROM MainTable LIMIT 5");

//        return new Response($dataSet);
        return $this->render('clickhouse/index.html.twig', [
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/mariadb/{page}", name="mariadb",  defaults={"page": 1})
     */
    public function getDataFromMariaDB(Request $request): Response
    {
        $page = $request->get('page');
        $offset = (($page - 1 ) * self::QUERY_LIMIT) > 0 ? ($page - 1 ) * self::QUERY_LIMIT : 0;
        $query = "SELECT * FROM MainTable LIMIT " . self::QUERY_LIMIT . " OFFSET $offset";
        $dataSet = $this->mariaDBConnection->fetchAll($query);

        return $this->render('mariadb/index.html.twig', [
            'rows' => $dataSet,
            'currentPage' => $page
        ]);
    }
}
