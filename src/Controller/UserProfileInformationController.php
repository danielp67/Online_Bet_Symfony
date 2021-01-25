<?php

namespace App\Controller;

use App\Entity\CardIdFile;
use App\Form\AddressType;
use App\Form\IdentityType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileInformationController extends AbstractController
{
    /**
     * @Route("/information", name="_information")
     */
    public function getUserInformation(): Response
    {
        $user = $this->getUser();
        $identityForm = $this->createForm(IdentityType::class, $user);

        $address = $user->getAddress();
        $addressForm = $this->createForm(AddressType::class, $address);

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => false,
            'editedAddress' => false,
        ]);
    }

    /**
     * @Route("/edit/identity", name="_edit_identity")
     */
    public function editUserIdentity(Request $request): Response
    {

        $user = $this->getUser();
        $address = $user->getAddress();

        $identityForm = $this->createForm(IdentityType::class, $user);
        $addressForm = $this->createForm(AddressType::class, $address);
        $identityForm->handleRequest($request);

        if ($identityForm->isSubmitted() && $identityForm->isValid()) {
            //On récupère le fichier transmit
            $file = $identityForm->get('justificatif')->getData();
            if ($file) {
                // On récupère le nom du fichier
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // On génère un nouveau nom de fichier
                // $safeFilename = $slugger->slug($originalFilename);
                $newFilename = uniqid() . '.' . $file->guessExtension();

                try {
                    //Copie du fichier sur le serveur
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $identityForm->addError(new FormError("Problème dans l\'envoi de la pièce-jointe"));
                }
                // Stockage du fichier dans la base de données
                $idCard = new CardIdFile();
                //mise à jour du nom du fichier
                $idCard->setName($newFilename);
                $user->setCardIdFile($idCard);
            } else {
                $identityForm->addError(new FormError("Vous devez fournir une pièce-jointe !"));
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('message', 'Votre identité à été modifiée avec succès !');
            return $this->redirectToRoute('app_profile_information');
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => true,
            'editedAddress' => false,
        ]);
    }

    /**
     * @Route("/edit/address", name="_edit_address")
     */
    public function editUserAddress(Request $request): Response
    {
        $user = $this->getUser();
        $address = $user->getAddress();

        $identityForm = $this->createForm(IdentityType::class, $user);
        $addressForm = $this->createForm(AddressType::class, $address);
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à été modifiée avec succès !');
            return $this->redirectToRoute('app_profile_information');
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => false,
            'editedAddress' => true,
        ]);
    }
}
