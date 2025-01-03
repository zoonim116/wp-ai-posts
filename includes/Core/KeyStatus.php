<?php

namespace Wpapi\Core;
enum KeyStatus: int
{
    case VALID = 1;
    case INVALID = 2;
    case UNDEFINED = 0;
}
