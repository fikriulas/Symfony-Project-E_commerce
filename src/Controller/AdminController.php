<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\Admin\messagesRepository;
use App\Controller\Admin;
use App\Repository\OrderdetailsRepository;
use App\Repository\OrdersRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(messagesRepository $messagesRepository)
    {

        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $emuye = $this->getDoctrine()->getManager();
        $sqluye = "SELECT COUNT(*) as say FROM user";
        $statementuye = $emuye->getConnection()->prepare($sqluye);
        $statementuye->execute();
        $resultuye = $statementuye->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM category";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultcategory = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultorders = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders WHERE status='New'";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultnew = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders WHERE status='Accepted'";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultAccepted = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders WHERE status='InShipping'";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultInShipping = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders WHERE status='Canceled'";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultCanceled = $statement->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT COUNT(*) as say FROM orders WHERE status='completed'";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $resultcompleted = $statement->fetchAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'result' => $result,
            'resultuye' => $resultuye,
            'resultcat' => $resultcategory,
            'resultorder' => $resultorders,
            'resultnew' => $resultnew,
            'resultAccepted' => $resultAccepted,
            'resultInShipping' => $resultInShipping,
            'resultCanceled' => $resultCanceled,
            'resultcompleted' => $resultcompleted,
        ]);
    }
    /**
     * @Route("/adminorders/{slug}", name="admin_orders_index")
     */
    public function orders($slug, OrdersRepository $ordersRepository)
    {
        $orders = $ordersRepository->findBy(['status'=>$slug]);

        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/adminorders/index.html.twig', [
            'orders'=>$orders,
            'result' => $result,
        ]);
    }
    /**
     * @Route("/adminorders/show/{id}", name="admin_orders_show")
     */
    public function show($id,Orders $orders,OrderdetailsRepository $orderdetailRepo)
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $orderdetail = $orderdetailRepo->findBy(
            ['orderid' => $id]
        );


        return $this->render('admin/adminorders/show.html.twig', [
            'order'=>$orders,
            'orderdetail' => $orderdetail,
            'result' => $result,
        ]);
    }
    /**
     * @Route("/adminorders/{id}/update", name="admin_orders_update")
     */
    public function update($id,Request $request,Orders $orders): \Symfony\Component\HttpFoundation\Response
    {


        $em = $this->getDoctrine()->getManager();
        $sql = "UPDATE orders SET shipinfo=:shipinfo,note=:note,status=:status WHERE id=:id";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('shipinfo',$request->request->get('shipinfo'));
        $statement->bindValue('note',$request->request->get('note'));
        $statement->bindValue('status',$request->request->get('status'));
        $statement->bindValue('id',$id);
        $statement->execute();

        $this->addFlash('succes','Bilgiler Başarıyla Güncellendi!');

        return $this->redirectToRoute('admin_orders_show',array('id'=>$id));

    }

}
