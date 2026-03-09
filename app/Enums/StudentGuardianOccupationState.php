<?php

namespace App\Enums;

enum StudentGuardianOccupationState: string
{
    case TEACHER = 'Teacher';
    case DOCTOR = 'Doctor';
    case ENGINEER = 'Engineer';
    case BUSINESS = 'Business';
    case FARMER = 'Farmer';
    case GOVERNMENT_EMPLOYEE = 'Government Employee';
    case PRIVATE_EMPLOYEE = 'Private Employee';
    case LABOUR = 'Labour';
    case DRIVER = 'Driver';
    case HOUSEWIFE = 'Housewife';
    case STUDENT = 'Student';
    case OTHER = 'Other';
}
