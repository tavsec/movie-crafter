<?php

namespace App;

enum MovieStatus: string
{
    case IN_PROGRESS = "in_progress";
    case FAILED = "failed";
    case SUCCESSFUL = "successful";
}
