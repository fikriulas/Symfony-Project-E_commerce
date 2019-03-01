<?php

namespace App\Controller;

use App\Entity\Orderdetails;
use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrderdetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ShopcardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="orders_index", methods="GET")
     */
    public function index(OrdersRepository $ordersRepository, ShopcardRepository $shopcardRepository): Response
    {
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        $user =$this->getUser();

        $user = $this->getUser();
        if($user!=null)
        {
            $userid = $user->getid();
            $totalsepet = $shopcardRepository->getUserShopCartTotal($userid);
            $countsepet = $shopcardRepository->getUserShopCartCount($userid);
        }else{
            $totalsepet=null;
            $countsepet=null;
        }

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT p.title,p.image, p.sprice, o.*
                FROM orderdetails o, product p     
                WHERE o.productid = p.id and o.userid=:userid";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid',$user->getid());
        $statement->execute();
        $shopcard = $statement->fetchAll();
        //dump($shopcard);
        //die();

        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findAll(),
            'shopcards' =>$shopcard,
            'cats'=>$cats,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }

    /**
     * @Route("/new", name="orders_new", methods="GET|POST")
     */
    public function new(Request $request,ShopcardRepository $shopcardRepository): Response
    {


        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        $usersepet = $this->getUser();
        if($usersepet!=null)
        {
            $userida = $usersepet->getid();
            $countsepet = $shopcardRepository->getUserShopCartCount($userida);
        }else{
            $totalsepet=null;
            $countsepet=null;
        }

        $user = $this->getUser();
        $userid = $user->getid();
        $total = $shopcardRepository->getUserShopCartTotal($userid);

        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('form-order',$submittedToken)) {
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();

                $order->setUserid($userid);
                $order->setAmount($total);
                $order->setStatus("New");

                $em->persist($order);
                $em->flush();

                $orderid = $order->getId();
                $shopcard = $shopcardRepository->getUserShopCard($userid);

                foreach($shopcard as $item){
                    $orderdetail = new Orderdetails();
                    $orderdetail->setOrderid($orderid);
                    $orderdetail->setUserid($user->getid());
                    $orderdetail->setProductid($item["productid"]);
                    $orderdetail->setPrice($item["sprice"]);
                    $orderdetail->setQuantity($item["quantity"]);
                    $orderdetail->setStatus("New");

                    $em->persist($orderdetail);
                    $em->flush();

                }
                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery('
                        DELETE FROM App\Entity\Shopcard s WHERE s.userid=:userid
                ')
                    ->setParameter('userid',$userid);
                $query->execute();
                return $this->redirectToRoute('orders_index');
            }
        }

        return $this->render('orders/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'cats'=>$cats,
            'total'=>$total,
            'count'=>$countsepet,
        ]);
    }

    /**
     * @Route("/{id}", name="orders_show", methods="GET")
     */
    public function show($id,Orderdetails $orderdetails,ShopcardRepository $shopcardRepository,OrderdetailsRepository $orderdetailRepo): Response
    {
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';
        $user = $this->getUser();
        $orderida = $orderdetails->getOrderid();
        $orderid = $id;

        $user = $this->getUser();
        if($user!=null)
        {
            $userid = $user->getid();
            $totalsepet = $shopcardRepository->getUserShopCartTotal($userid);
            $countsepet = $shopcardRepository->getUserShopCartCount($userid);
        }else{
            $totalsepet=null;
            $countsepet=null;
        }

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT o.*
                FROM orders o    
                WHERE o.id=:orderid";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('orderid',$orderida);
        $statement->execute();
        $oders = $statement->fetchAll();


        $orderdetail = $orderdetailRepo->findBy(
            ['id'=>$orderid]
        );


        return $this->render('orders/show.html.twig', [
            'orderdetail' =>$orderdetail,
            'cats'=>$cats,
            'oders'=>$oders,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }
    /**
     * @Route("/{id}/ishow", name="iorders_show", methods="GET")
     */
    public function ishow(Orders $orders,OrderdetailsRepository $orderdetailsRepository): Response
    {
        $user = $this->getUser();
        $orderid = $orders->getId();
        dump($orderid);
        die();
        $orderdetail = $orderdetailsRepository->findBy(
        ['orderid' =>orderid]
        );
    }

    /**
     * @Route("/{id}/edit", name="orders_edit", methods="GET|POST")
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_index', ['id' => $order->getId()]);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_delete", methods="DELETE")
     */
    public function delete(Request $request, Orders $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($order);
            $em->flush();
        }

        return $this->redirectToRoute('orders_index');
    }
    public function fetchCategoryTreeList($parent =0,$user_tree_array=''){
        if(!is_array($user_tree_array))
            $user_tree_array = array();

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM category WHERE status='true' AND parentid =".$parent;
        $statement = $em->getConnection()->prepare($sql);
        //$statement->bindValue('parentid',$parent);
        $statement->execute();
        $result = $statement->fetchAll();

        if(count($result)>0){
            $user_tree_array[] = "<ul>";
            foreach ($result as $row)
            {    //{{ path('admin_user_update', {'id': user.id}) }}
                $user_tree_array[] = "<li> <a href='/category/".$row['id']."'>". $row['title']."</a>";
                $user_tree_array = $this->fetchCategoryTreeList($row['id'],$user_tree_array);
            }
            $user_tree_array[] = "</li></ul>";
        }
        return $user_tree_array;
    }
}
