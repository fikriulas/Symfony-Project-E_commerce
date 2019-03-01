<?php

namespace App\Controller\Userpanel;

use App\Entity\User;
use App\Repository\ShopcardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/userpanel")
 */
class userpanelController extends AbstractController
{
    /**
     * @Route("/", name="userpanel_userpanel")
     */
    public function index(ShopcardRepository $shopcardRepository)
    {
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

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        return $this->render('userpanel/userpanel/show.html.twig', [
            'cats'=>$cats,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }

    /**
     * @Route("/edit", name="userpanel_edit", methods="GET|POST")
     */
    public function edit(Request $request, ShopcardRepository $shopcardRepository): Response
    {
        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

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

        $usersession = $this->getUser();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($usersession->getid());
        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('uye-edit',$submittedToken)){
            if($request->isMethod('POST')){
                $user->setPhone($request->get('phone'));
                $user->setEmail($request->get('email'));
                $user->setPassword($request->get('password'));
                $user->setAddress($request->get('address'));
                $user->setCity($request->get('city'));
                $user->setName($request->get('name'));
                $this->addFlash('succes',"Güncelleme Başarılı!");
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('userpanel_show');
            }
        }

        return $this->render('userpanel/userpanel/edit.html.twig', [
            'controller_name' => 'userpanelController',
            'cats'=>$cats,
            'user' => $user,
            'total'=> $totalsepet,
            'count'=>$countsepet,
        ]);
    }



    /**
     * @Route("/show", name="userpanel_show", methods="GET|POST")
     */
    public function show(Request $request, ShopcardRepository $shopcardRepository)
    {
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

        $cats = $this->fetchCategoryTreeList();
        $cats[0] = '<ul id="menu-v" class="category-list">';

        return $this->render('userpanel/userpanel/show.html.twig', [
            'cats'=>$cats,
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

}
