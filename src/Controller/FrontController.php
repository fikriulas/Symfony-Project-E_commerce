<?php

namespace App\Controller;

use App\Entity\Admin\messages;
use App\Entity\Commentproduct;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\UserlistType;
use App\Form\UserType;
use App\Repository\Admin\CategoryRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\Admin\messagesRepository;
use App\Form\Admin\messagesType;
use App\Repository\CommentproductRepository;
use App\Repository\ShopcardRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form\UserLoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SettingRepository $settingRepository, ShopcardRepository $shopcardRepository)
    {
        $data = $settingRepository->findAll();

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

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
        $sql = "SELECT * FROM product WHERE status='true' ORDER BY RAND()";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $slider = $statement->fetchAll();


        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'data'=>$data,
            'cats'=>$cats,
            'slider'=>$slider,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);

    }
    /**
     * @Route("/productcomment/{id}", name="product_comment", methods="GET|POST")
     */
    public function productcomment($id,Request $request)
    {
        $Commentproduct = new Commentproduct();
        $mail = $request->request->get('email');
        $content = $request->request->get('content');
        $time = new \DateTime();
        $tarih = $time->format('Y-m-d H:i:s');

        $submittedToken = $request->request->get('token');
        if ($request) {
            if ($this->isCsrfTokenValid('form-message', $submittedToken)) {
                $em = $this->getDoctrine()->getManager();
                $Commentproduct->setProductid($id);
                $Commentproduct->setEmail($mail);
                $Commentproduct->setContent($content);
                $Commentproduct->setTarih($tarih);
                $em->persist($Commentproduct);
                $em->flush();
                $this->addFlash('succes',"Yorumunuz Gönderildi!");
                return $this->redirectToRoute('product_detail',array('id'=>$id));
            }
        }

        return $this->redirectToRoute('product_detail',array('id'=>$id));

    }
    /**
     * @Route("/hakkimizda", name="hakkimizda" , methods="GET|POST")
     */
    public function hakkımızda(SettingRepository $settingRepository,Request $request, ShopcardRepository $shopcardRepository)
    {
        $message = new messages();
        $form = $this->createForm(messagesType::class, $message);
        $form->handleRequest($request);
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

        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if($this->isCsrfTokenValid('form-message',$submittedToken))
            {
                $message->setStatus("Yeni");
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
                $this->addFlash('succes',"Mesaj Gönderildi!");
                return $this->redirectToRoute('hakkimizda');
            }
            else{
                $this->addFlash('error',"Mesaj Gönderilemedi!");
            }

        }
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';
        $data = $settingRepository->findAll();

        return $this->render('front/hakkimizda.html.twig', [
            'controller_name' => 'FrontController',
            'data'=>$data,
            'cats'=> $cats,
            'message' => $message,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }
    /**
     * @Route("/iletisim", name="iletisim" , methods="GET|POST")
     */
    public function iletisim(SettingRepository $settingRepository,Request $request, ShopcardRepository $shopcardRepository)
    {

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


        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';
        $data = $settingRepository->findAll();

        return $this->render('front/iletisim.html.twig', [
            'controller_name' => 'FrontController',
            'data'=>$data,
            'cats'=> $cats,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }
    /**
     * @Route("/referanslar", name="referanslar")
     */
    public function referanslar(SettingRepository $settingRepository, ShopcardRepository $shopcardRepository)
    {

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

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';
        $data = $settingRepository->findAll();
        return $this->render('front/referanslar.html.twig', [
            'controller_name' => 'FrontController',
            'data'=>$data,
            'cats'=> $cats,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }
    /**
     * @Route("/category/{catid}", name="category_products", methods="GET|POST")
     */
    public function categoryProducts($catid, CategoryRepository $categoryRepository,ShopcardRepository $shopcardRepository)
    {
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        $data = $categoryRepository->findBy(
            ['id' => $catid]
        );

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
        $sql = 'SELECT * FROM product WHERE status="true" AND categoryid = :catid';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('catid',$catid);
        $statement->execute();
        $products = $statement->fetchAll();

        return $this->render('front/frontproducts.html.twig', [
            'data'=>$data,
            'products'=>$products,
            'cats'=> $cats,
            'total'=> $totalsepet,
            'count'=>$countsepet,
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
     * @Route("/product/{id}", name="product_detail", methods="GET|POST")
     */
    public function productDetail($id,ProductRepository $productRepository, ImageRepository $imageRepository,ShopcardRepository $shopcardRepository, CommentproductRepository $commentproductRepository)
    {

        $images = $imageRepository->findBy(
            ['product_id'=>$id]
        );

        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM commentproduct WHERE productid=:id";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->bindValue('id',$id);
        $statementcount->execute();
        $result = $statementcount->fetchAll();
        $commentpro = $commentproductRepository->findBy(
            ['productid'=>$id]
        );

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

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

        $data = $productRepository->findBy(
            ['id' => $id]
        );


        /*
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM product WHERE status='true' ORDER BY ID DESC LIMIT 4";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $slider = $statement->fetchAll();
        */
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM product WHERE status='true' ORDER BY RAND() ";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $slider = $statement->fetchAll();



        return $this->render('front/product_detail.html.twig', [
            'data'=>$data,
            'cats'=>$cats,
            'images'=>$images,
            'total'=> $totalsepet,
            'count'=>$countsepet,
            'slider' => $slider,
            'comment' => $commentpro,
            'result' =>$result,
        ]);

    }

    /**
     * @Route("/newuser", name="new_user" , methods="GET|POST")
     */
    public function newuser(Request $request,UserRepository $userRepository, ShopcardRepository $shopcardRepository): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $usersepet = $this->getUser();
        if($usersepet!=null)
        {
            $userid = $usersepet->getid();
            $totalsepet = $shopcardRepository->getUserShopCartTotal($userid);
            $countsepet = $shopcardRepository->getUserShopCartCount($userid);
        }else{
            $totalsepet=null;
            $countsepet=null;
        }

        $submittedToken = $request->request->get('token');
        if ($form->isSubmitted()) {
            if($this->isCsrfTokenValid('uye-ol',$submittedToken))
            {
                //echo $user->getEmail();
                //die();
                $emaildata= $userRepository->findBy(
                    ['email' => $user->getEmail()]
                );
                if($emaildata==null){
                    $em = $this->getDoctrine()->getManager();
                    //$user->setRoles("ROLE_USER");
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('succes',"Üye Oldunuz, Giriş Yapın!");
                    return $this->redirectToRoute('app_login');
                }
                else{
                    $this->addFlash('error',$user->getEmail()." Adında bir kullanıcı zaten var.");
                    return $this->redirectToRoute('new_user');
                }
            }
        }
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        return $this->render('front/newuser.html.twig', [
            'controller_name' => 'FrontController',
            'cats'=> $cats,
            'user'=>$user,
            'total'=> $totalsepet,
            'count'=>$countsepet,

        ]);
    }

}

