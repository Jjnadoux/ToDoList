<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="Home")
     *
     * @return Response
     */
    
    public function Home(EntityManagerInterface $em): Response
    {
        $lists = $em->getRepository(Task::class)->findAllListTasks();
        dump($lists);
        return $this->render('home.html.twig', [
            "lists" =>$lists,
        ]);
    }

    /**
     * @Route("/todolistDetail/{id}", name="todolist_detail")
     * @param integer $id
     */
    public function ToDoListDetail(EntityManagerInterface $em, $id): Response
    {
        $listDetail = $em->getRepository(Task::class)->findAllTasksByList($id);
        return $this->render('toDolistDetail.html.twig', [
            'listDetail' => $listDetail,
        ]);
    }
}