<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/users', name: 'app_user_gestion', methods: ['GET', 'POST'])]
    public function userGestion(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        if ($request->isMethod('POST')) {
            $userId = $request->request->get('user_id');
            $action = $request->request->get('action');

            $user = $userRepository->find($userId);
            if ($user) {
                if ($action === 'validate') {
                    // Ajouter le rôle ROLE_USER
                    $roles = $user->getRoles();
                    if (!in_array('ROLE_USER', $roles)) {
                        $roles[] = 'ROLE_USER';
                        $user->setRoles($roles);
                    }
                } elseif ($action === 'deactivate') {
                    // Retirer le rôle ROLE_USER
                    $roles = $user->getRoles();
                    if (($key = array_search('ROLE_USER', $roles)) !== false) {
                        unset($roles[$key]);
                        $user->setRoles(array_values($roles));
                    }
                }
                $entityManager->flush();
                $this->addFlash('success', 'Action effectuée avec succès.');
                return $this->redirectToRoute('app_user_gestion');
            }
        }

        return $this->render('admin/user_gestion.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/posts', name: 'app_post_gestion', methods: ['GET'])]
    public function postGestion(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('admin/post_gestion.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/comments', name: 'app_comment_gestion', methods: ['GET', 'POST'])]
    public function commentGestion(CommentRepository $commentRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $comments = $commentRepository->findAll();

        if ($request->isMethod('POST')) {
            $commentId = $request->request->get('comment_id');
            $action = $request->request->get('action');

            $comment = $commentRepository->find($commentId);
            if ($comment) {
                if ($action === 'approve') {
                    $comment->setStatus('approuvé');
                } elseif ($action === 'disapprove') {
                    $comment->setStatus('rejeté');
                }
                $entityManager->flush();
                $this->addFlash('success', 'Commentaire mis à jour avec succès.');
                return $this->redirectToRoute('app_comment_gestion');
            }
        }

        return $this->render('admin/comment_gestion.html.twig', [
            'comments' => $comments,
        ]);
    }
    
    #[Route('/categories', name: 'app_category_index', methods: ['GET'])]
    public function categoryGestion(): Response
    {
        return $this->render('admin/category_gestion.html.twig');
    }
}
