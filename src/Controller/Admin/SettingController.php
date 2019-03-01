<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Setting;
use App\Form\Admin\SettingType;
use App\Repository\Admin\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/setting")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/", name="admin_setting_index", methods="GET")
     */
    public function index(SettingRepository $settingRepository): Response
    {

        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        // settings tablosunda veri yoksa veriyi ekler, sonra edit kısmına gönderir.
        $setdata = $settingRepository->findAll();
        if(!$setdata){
            //echo "veritabanı boş";
            $setting = new Setting();
            $em = $this->getDoctrine()->getManager();
            $setting->setTitle("Site");
            $em->persist($setting);
            $em->flush();
            $setdata= $settingRepository->findAll();
            //dump($setdata);
            //die();
        }
        return $this->redirectToRoute('admin_setting_edit', ['id' => $setdata[0]->getId(),'result' => $result,]);
        //return $this->render('admin/setting/index.html.twig', ['settings' => $settingRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin_setting_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $setting = new Setting();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($setting);
            $em->flush();

            return $this->redirectToRoute('admin_setting_index');
        }

        return $this->render('admin/setting/new.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_setting_show", methods="GET")
     */
    public function show(Setting $setting): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/setting/show.html.twig', ['setting' => $setting,'result' => $result,]);
    }

    /**
     * @Route("/{id}/edit", name="admin_setting_edit", methods="GET|POST")
     */
    public function edit(Request $request, Setting $setting): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('succes',"Ayarlar Güncellendi!");
            return $this->redirectToRoute('admin_setting_index', ['id' => $setting->getId()]);
        }

        return $this->render('admin/setting/edit.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_setting_delete", methods="DELETE")
     */
    public function delete(Request $request, Setting $setting): Response
    {


        if ($this->isCsrfTokenValid('delete'.$setting->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($setting);
            $em->flush();
        }

        return $this->redirectToRoute('admin_setting_index');
    }
}
