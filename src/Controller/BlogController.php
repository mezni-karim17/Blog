<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package App\Controller
 */

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {

        $posts = $this->getDoctrine()->getRepository(Post::class)->getAllPosts();
        return $this->render("index.html.twig", [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @return Response
     * @param Post $post
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment;
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }

        // $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        return $this->render("read.html.twig", [
            "post" => $post,
            "form" => $form->createView()
        ]);
    }
}
