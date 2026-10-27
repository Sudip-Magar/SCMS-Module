<?php

namespace App\Enums;

enum StudentDocumentTypeState : string
{
    case SEE_CERTIFICATE = 'SEE Certificate';
    case SEE_MARKSHEET = 'SEE Marksheet';
    case CHARACTER_CERTIFICATE = 'Character Certificate';
    case MIGRATION_CERTIFICATE = 'Migration Certificate';
    case TRANSFER_CERTIFICATE = 'Transfer Certificate';
    case PROVISIONAL_CERTIFICATE = 'Provisional Certificate';
    case PLUS_TWO_CERTIFICATE = '+2 Certificate';
    case PLUS_TWO_MARKSHEET = '+2 Marksheet';

    case BACHELOR_CERTIFICATE = 'Bachelor Certificate';
    case BACHELOR_TRANSCRIPT = 'Bachelor Transcript';

    case MASTER_CERTIFICATE = 'Master Certificate';
    case MASTER_TRANSCRIPT = 'Master Transcript';

    case EQUIVALENCE_CERTIFICATE = 'Equivalence Certificate';

    // Identity Documents
    case BIRTH_CERTIFICATE = 'Birth Certificate';
    case CITIZENSHIP = 'Citizenship Certificate';
    case PASSPORT = 'Passport';
}
