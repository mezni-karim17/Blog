<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
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
    public function index(PostRepository $postRepository): Response
    {

        $array1 = array(5, 6, 7, 8, 9);
        $res = [];

        $sum = 0;
        foreach ($array1 as $value) {
            $sum = $sum + $value;

            $res[] = $value + 2;
        }

        foreach ($array1 as $element) {
            $res[] = $element;
        }





        // dump($res);
        // dd($sum);

        for ($i = 0; $i < count($array1); $i++) {
            $sum = $sum + $array1[$i];

            $array1[$i] = $array1[$i] + 2;


            echo $array1[$i] . "<br/>";
        }
        echo   "<br/>" . $sum . "<br/>";


        $posts = $postRepository->getPaginatedPosts(1, 10);

        $tabs = [
            "c1" => "rouge",
            "c2" => "blanc",
            "c3" => "green",
            "c4" => "bananne",
            "c5" => $posts,
        ];

        $names = [];
        foreach ($tabs as $key => $tab) {
            if (is_array($tab)) {

                foreach ($tab as $key => $tb) {
                    if ($tb instanceof Post) {
                        $names[] = $tb->getTitle();
                    }
                }
            }
        }
        dump($names);
        die;
        foreach ($posts as $key => $post) {
            dump($key);
            dump($post->getId());
        }
        die();

        return $this->render("index.html.twig", [
            "posts" => $posts,

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
