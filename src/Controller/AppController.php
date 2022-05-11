<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $list = $em->getRepository(ToDoList::class)->find($id);
        $listDetail = $em->getRepository(Task::class)->findAllTasksByList($id);
        dump($listDetail);
        return $this->render('toDolistDetail.html.twig', [
            'list' => $list,
            'listDetail' => $listDetail,
        ]);
    }

    /**
     * 
     * @Route("/task/{id}/changeStatut", name="task_changeStatut")
     * @param integer $id
     */
    public function ChangeDone(EntityManagerInterface $em, $id, Request $request)
    {
        $statut = $request->get('statut');
        $task = $em->getRepository(Task::class)->find($id);
        $task->setIsDone($statut);
        $em->flush();

        return new JsonResponse('ok');
    }

    /**
     * @Route("/task/add", name="task_add")
     */
    public function AddTask(EntityManagerInterface $em, Request $request)
    {
        $title = $request->get('title');
        $isDone = $request->get('isDone');
        $list = $em->getRepository(ToDoList::class)->find($request->get('idlist'));

        $newTask = new Task();
        $newTask->setTitle($title);
        $newTask->setIsDone($isDone);
        $newTask->setIdToDoList($list);
        $em->persist($newTask);
        $em->flush();
    }
}