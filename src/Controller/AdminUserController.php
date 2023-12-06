<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\RegistrationType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users/{page<\d+>?1}', name: 'admin_users_index')]
    public function index(PaginationService $pagination,int $page): Response
    {
        $pagination->setEntityClass(User::class)
        ->setPage($page)
        ->setLimit(10);

        return $this->render('admin/user/index.html.twig', [
            'pagination'=>$pagination
        ]);
    }
    #[Route("/admin/users/{id}/edit", name:"admin_users_edit")]
    public function edit(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'user n°".$user->getId()." a été modifié"
            );
        }
        return $this->render("admin/user/edit.html.twig",[
            'user' => $user,
            'myForm' => $form->createView()
        ]);
    }
    #[Route("/admin/users/{id}/delete", name:"admin_users_delete")]
    public function delete(User $user, EntityManagerInterface $manager): Response
    {
        $this->addFlash(
            'success',
            ' <strong>'.$user->getFullName().'</strong> a bien été supprimé'
        );
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_bookings_index');
    }
    
}
