<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminBookingController extends AbstractController
{
    #[Route('/admin/bookings', name: 'admin_bookings_index')]
    public function index(BookingRepository $repo): Response
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

      /**
     * Permet d'éditer les réservations (pas déontologique)
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

     #[Route("/admin/bookings/{id}/edit", name:"admin_bookings_edit")]
     public function edit(Booking $booking, Request $request, EntityManagerInterface $manager): Response{
        
            $form = $this->createForm(AdminBookingType::class, $booking);$form->handleRequest($request);
     
             if($form->isSubmitted() && $form->isValid())
             {
                 $manager->persist($booking);
                 $manager->flush();
     
                 $this->addFlash(
                     'success',
                     "La réservation n°".$booking->getId()." a été modifiée"
                 );
             }
     
             return $this->render("admin/booking/edit.html.twig",[
                 'booking' => $booking,
                 'myForm' => $form->createView()
             ]);
         }
     
         
          

     #[Route("/admin/bookings/{id}/delete", name:"admin_bookings_delete")]
     public function delete(Booking $booking, EntityManagerInterface $manager): Response{$this->addFlash("success","La réservation n°".$booking->getId()." a bien été supprimée");
     
             $manager->remove($booking);
             $manager->flush();
     
             return $this->redirectToRoute("admin_bookings_index");
         }
}