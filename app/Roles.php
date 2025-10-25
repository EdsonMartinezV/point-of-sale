<?php

namespace App;

enum Roles:String
{
    case ADMIN = 'admin';
    case OWNER = 'owner';
    case CASHIER = 'cashier';
}
