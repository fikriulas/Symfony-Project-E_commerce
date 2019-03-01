<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Category;
use App\Form\Admin\CategoryType;
use App\Repository\Admin\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category_index", methods="GET")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'result' => $result,
        ]);
    }

    /**
     * @Route("/new", name="admin_category_new", methods="GET|POST")
     */
    public function new(Request $request,CategoryRepository $CategoryRepository): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $categoryList = $CategoryRepository->findBy(['parentid' => 0]);
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'categoryList' => $categoryList,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_category_show", methods="GET")
     */
    public function show(Category $category): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_category_edit", methods="GET|POST")
     */
    public function edit(Request $request,CategoryRepository $CategoryRepository ,Category $category,$id): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $categoryList = $CategoryRepository->findBy(['parentid' => 0]);
        $parentID = $category->getParentid();
        $catid = $CategoryRepository->findBy(
            ['id' => $parentID]
        );
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_category_index', ['id' => $category->getId()]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'catid' => $catid,
            'categoryList' => $categoryList,
            'result' => $result,

        ]);
    }

    /**
     * @Route("/{id}", name="admin_category_delete", methods="DELETE")
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('admin_category_index');
    }
}
