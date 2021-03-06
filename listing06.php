<?php

class IdeaController extends Zend_Controller_Action
{
    public function rateAction()
    {
        $ideaId = $this->request->getParam('id');
        $rating = $this->request->getParam('rating');

        $ideaRepository = new RedisIdeaRepository();
        $useCase = new RateIdeaUseCase($ideaRepository);
        $response = $useCase->execute(
            new RateIdeaRequest($ideaId, $rating)
        );

        $this->redirect('/idea/'.$response->idea->getId());
    }
}

class RateIdeaRequest
{
    /**
     * @var int
     */
    public $ideaId;

    /**
     * @var int
     */
    public $rating;

    public function __construct($ideaId, $rating)
    {
        $this->ideaId = $ideaId;
        $this->rating = $rating;
    }
}

class RateIdeaResponse
{
    /**
     * @var Idea
     */
    public $idea;

    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }
}

class RateIdeaUseCase
{
    // ...

    public function execute($request)
    {
        $ideaId = $request->ideaId;
        $rating = $request->rating;

        // ...

        return new RateIdeaResponse($idea);
    }
}
