<?php
class RedisPostRepository implements PostRepository
{
    private $client;

    public function __construct()
    {
        $client = new Predis\Client();
        $post = $client->get('post_'.$postId);
        if (!$post) {
            throw new Exception('Post does not exist');
        }

        $post->addVote($rating);
        $client->set('post_'.$postId, $post);

        $this->redirect('/post/'.$postId);

         $this->client = new SQLiteDatabase('mydb');
    }

    public function find($id)
    {
        $row = @$this->client->query(
            'SELECT * FROM posts WHERE id = '.$id
        );

        if (!$postRow) {
            return null;
        }

        return new Post(
            $row['id'],
            $row['title'],
            $row['author'],
            $row['rating'],
            $row['votes']
        );
    }

    public function save($post)
    {
        $this->client->set('post_'.$post->getId(), $post);
    }
}

class PostController extends Zend_Controller_Action
{
    public function voteAction()
    {
        $postId = $this->request->getParam('id');
        $rating = $this->request->getParam('rating');

        $postRepository = new RedisPostRepository();
        $post = $postRepository->find($postId);
        if (!$post) {
            throw new Exception('Post does not exist');
        }

        $post->addVote($rating);
        $postRepository->save($post);

        $this->redirect('/post/'.$postId);
    }
}