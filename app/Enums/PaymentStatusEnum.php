<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Canceled = 'canceled';
    case UnPaid = 'unpaid';
}