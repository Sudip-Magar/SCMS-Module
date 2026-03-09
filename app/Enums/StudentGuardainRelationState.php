<?php

namespace App\Enums;

enum StudentGuardainRelationState : string
{
    case MOTHER = 'Mother';
    case FATHER = 'Father';
    case GRANDMOTHER = 'Grandmother';
    case GRANDFATHER = 'Grandfather';
    case BORTHER = 'Brother';
    case SISTER = 'Sister';
    case UNCLE = 'Uncle';
    case AUNT = 'Aunt';
}
