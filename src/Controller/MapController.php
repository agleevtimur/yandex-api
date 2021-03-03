<?php

declare(strict_types=1);

namespace Controller;

use Entity\Mark;
use Service\Statistics;

class MapController extends AbstractController
{
    public function index()
    {
        $this->sendView('skeleton_page');
    }

    public function renderPage()
    {
        $this->sendJson(
            [
                'view' => $this->getViewContent('main_page'),
                'marks' => Mark::getAll(),
                'statistics' => Statistics::get()
            ]
        );
    }

    public function saveMark()
    {
        $data = json_decode($this->getContent(), true);
        $mark = new Mark($data['x'], $data['y'], $data['distance']);
        $mark->save();

        $this->sendJson();
    }

    public function reset()
    {
        Mark::deleteAll();
        $this->sendJson();
    }

    public function updateStatistics()
    {
        $this->sendJson(['statistics' => Statistics::get()]);
    }
}