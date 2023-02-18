<?php

declare(strict_types=1);

namespace App\Service;

enum SortPosition
{
    case AsFirst;
    case Between;
    case AsLast;
}
