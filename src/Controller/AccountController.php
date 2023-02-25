<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/*  
    @Route("/account") => placé là factorise les routes dans la class
*/
    
/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account", methods="GET")
     */
    public function show(): Response
    {
        return $this->render('account/account.html.twig');
    }

    /**
     * @Route("/edit", name="app_account_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Account updated successfully!');
            return $this->redirectToRoute('app_account');            
        }
        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change-password", name="app_account_change_password", methods={"GET", "POST"})
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        /* 
            Ici on cree le formulaire et on lui ajoute une option (voir createForm (ctrl click) )
            par defaut (que l'on definie dans configureOptions ( ctrl click sur ChangePasswordFormType ) la class  {function }) pour qu'il n'affiche les mêmes champ que le formulaire dans
            ResetPasswordController
        */
        $form = $this->createForm(ChangePasswordFormType::class, null, [
           'current_password_is_required' => true, 
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hachPassword = $passwordEncoder->hashPassword(
                $user,
                $form->get('newPassword')->getData()
            );
            $user->setPassword($hachPassword);
            
            $em->flush();

            $this->addFlash('success', 'Password updated successfuly!');

            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/change-password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
