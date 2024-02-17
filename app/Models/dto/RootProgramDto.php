<?php

namespace App\Models\dto;

class RootProgramDto
{
    public $program;
    public $totalVote;

    public function __construct($program, $totalVote)
    {
        $this->program = $program;
        $this->totalVote = $totalVote;
    }
}