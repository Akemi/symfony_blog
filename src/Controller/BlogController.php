<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Datetime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("/{page}", name="home", requirements={"page"="\d+"})
     */

    public function page($page = 1)
    {
        $postRep = $this->getDoctrine()->getRepository(Post::class);

        $posts = $postRep->findBy(array(), array('date' => 'DESC'), 5, 5*($page-1));
        $pageCount = ceil($postRep->postCount()/5) ?: 1;

        return $this->render('blog/index.html.twig', ['posts' => $posts,
                                                            'currentPage' => $page,
                                                            'pageCount' => $pageCount]);
    }

    /**
     * @Route("/post/{id}", name="page", requirements={"page"="\d+"}, methods={"GET", "POST"})
     */

    public function post($id, Request $request)
    {
        $user = $this->getUser();
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        $newComment = new Comment();
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $newComment->setUser($user);
            $newComment->setName($user->getUsername());
        }
        $formBuilder = $this->createFormBuilder($newComment);
        if (!$this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $formBuilder->add('name', TextType::class, array('label' => 'Name'));
        }
        $formBuilder
            ->add('url', TextType::class, array('label' => 'URL'))
            ->add('content', TextareaType::class, array('label' => 'Text'))
            ->add('save', SubmitType::class, array('label' => 'Submit'));

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $subComment = $form->getData();
            $subComment
                ->setDate(new DateTime())
                ->setPost($post);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subComment);
                $entityManager->flush();

                return $this->redirect($request->getUri());
            }
        }

        //dump($post->getComments());
        //echo $post->getId();

        /**
         * some bloody HACK because OneToMany relation doesn't work
         */
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(array('post' => $post), array('date' => 'ASC'));
        //dump($comments);

        return $this->render('blog/post.html.twig', ['post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
    }

    /**
     * @Route("/post/{id}", name="delete_comment", requirements={"page"="\d+"}, methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteComment($id, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $comment = $this->getDoctrine()
                ->getRepository(Comment::class)
                ->find($request->getContent());

            if ($comment != NULL) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->remove($comment);
                $em->flush();
                return new JsonResponse(['success' => true, 'data' => 'deleted comment '.$comment->getId()]);
            }

            return new JsonResponse(['success' => true, 'data' => 'nothing to delete']);
        } else {
            return new JsonResponse(['success' => false]);
        }
    }

    /**
     * @Route("/user/{id}", name="user", requirements={"page"="\d+"})
     */
    public function user($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        return $this->render('blog/user.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/create", name="create")
     * @IsGranted("ROLE_AUTHOR")
     */
    public function create(Request $request)
    {
        $author = $this->getUser();

        $newPost = new Post();
        $form = $this->createFormBuilder($newPost)
            ->add('title', TextType::class, array('label' => 'Title'))
            ->add('image', TextType::class, array('label' => 'Image URL'))
            ->add('content', TextareaType::class, array('label' => 'Text'))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $subPost = $form->getData();
            $subPost
                ->setDate(new DateTime())
                ->setAuthor($author);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($subPost);
                $entityManager->flush();

                dump($subPost->getId());

                $url = $this->generateUrl(
                    'page',
                    array('id' => $subPost->getId())
                );

                return $this->redirect($url);
            }
        }

        return $this->render('blog/create.html.twig', ['form' => $form->createView()]);
    }
}
