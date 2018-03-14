<?php

namespace Stratum\Original\HTTP\Response;

use Stratum\Original\HTTP\Cleaner\BufferCleaner;
use Stratum\Original\HTTP\Response;
use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;
use Stratum\Original\Presentation\Balance\Flusher;
use Stratum\Original\Presentation\EOMWriter;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\PartialView;
use Stratum\Original\Presentation\View;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class HTML extends Response
{
    use className;

    protected $masterPagePath = 'Master.html';

    public function __construct(\Symfony\Component\HttpFoundation\Response $response)
    {
        $this->view = new View;
        $this->headView = (new PartialView
            )->from('Master-head.html');

        $this->view->setHeadView($this->headView);
        
        parent::__construct($response);
    }

    public function from($path)
    {
        $this->view->from($path);

        return $this;
    }

    public function with(array $variables)
    {
        $this->headView->with($variables);
        $this->view->with($variables);

        return $this;
    }

    public function cannotBeCached()
    {
        $this->view->cannotBeCached();

        return $this;
    }

    public function useMasterPage($masterPagePath)
    {
        $this->masterPagePath = $masterPagePath;

        $this->headView->from(str_replace('.html', '-head.html', $masterPagePath));

        $this->view->setMasterPagePath($masterPagePath);
        
        return $this;
    }

    public function send()
    {
        $this->view->load();
    }

    protected function ContentType()
    {
        return 'text/html';
    }

    protected function Body()
    {
        return $this->body;
    }

}