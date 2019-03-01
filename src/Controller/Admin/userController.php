<?php

namespace App\Controller\Admin;

use App\Entity\Userlist;
use App\Entity\User;
use App\Form\UserlistType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class userController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/user/index.html.twig', [
            'controller_name' => 'userController',
            'user' => $user,
            'result' => $result,
        ]);
    }
    /**
     * @Route("/admin/user/ekle", name="admin_user_ekle",methods="GET|POST")
     */
    public function uyeEkle(Request $request):Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $user = new Userlist();
        $form = $this->createForm(UserlistType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/uyeEkle.html.twig', [
            'form' => $form->createView(),
            'csrf_protection'=>false,
            'result' => $result,
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */
    public function delete(User $user)
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/admin/user/update/{id}", name="admin_user_update",methods="GET|POST")
     */
    public function update(Request $request, User $user):Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/uyeUpdate.html.twig', [
            'user'=>$user,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }


}
