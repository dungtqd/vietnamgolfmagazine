<?php

namespace App\Models\dto;

class ProgramDto
{
    public $program;
    public $totalVote;

    public function __construct($program, $totalVote)
    {
        $this->program = $program;
        $this->totalVote = $totalVote;
    }
}