<?php

namespace App\Controller;

use App\Entity\Shopcard;
use App\Form\ShopcardType;
use App\Repository\Admin\ImageRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\ShopcardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopcard")
 */
class ShopcardController extends AbstractController
{
    /**
     * @Route("/", name="shopcard_index", methods="GET|POST")
     */
    public function index(ShopcardRepository $shopcardRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); //login kontrolu.
        $user =$this->getUser();

        $user = $this->getUser();
        $userid = $user->getid();
        $total = $shopcardRepository->getUserShopCartTotal($userid);
        $count = $shopcardRepository->getUserShopCartCount($userid);

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT p.title,p.image, p.sprice, s.*
                FROM shopcard s, product p        
                WHERE s.productid = p.id and s.userid=:userid";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid',$user->getid());
        $statement->execute();
        $shopcard = $statement->fetchAll();

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';
        return $this->render('shopcard/index.html.twig', [
            //'shopcards' => $shopcardRepository->findAll(),
            'shopcards' =>$shopcard,
            'cats'=>$cats,
            'total'=> $total,
            'count'=>$count,

        ]);
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

    /**
     * @Route("/new", name="shopcard_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $shopcard = new Shopcard();
        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('add-item',$submittedToken)){


            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $user = $this->getUser();
                $shopcard->setUserid($user->getid());

                $em->persist($shopcard);
                $em->flush();

                return $this->redirectToRoute('shopcard_index');
            }
        }

        return $this->render('shopcard/new.html.twig', [
            'shopcard' => $shopcard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="shopcard_show", methods="GET")
     */
    public function show(Shopcard $shopcard): Response
    {
        echo "showdayim";
        die();
        return $this->render('shopcard/show.html.twig', ['shopcard' => $shopcard]);
    }

    /**
     * @Route("/{id}/edit", name="shopcard_edit", methods="GET|POST")
     */
    public function edit(Request $request, Shopcard $shopcard): Response
    {
        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shopcard_index', ['id' => $shopcard->getId()]);
        }

        return $this->render('shopcard/edit.html.twig', [
            'shopcard' => $shopcard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="shopcard_delete")
     */
    public function delete(Request $request, Shopcard $shopcard): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopcard);
            $em->flush();
            $this->addFlash('succes',"Ürün Silindi!");
        return $this->redirectToRoute('shopcard_index');
    }
}
