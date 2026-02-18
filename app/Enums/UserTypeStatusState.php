<?php

namespace App\Enums;

enum UserTypeStatusState : string
{
    case STUDENT = 'Student';
    case TEACHER = 'Teacher';
    case EMPLOYEE = 'Employee';
}
