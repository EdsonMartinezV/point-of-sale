<?php

namespace App;

enum Percentages: string
{
    case FIRST_WHOLESALE_PERCENTAGE = 'first_wholesale_percentage';
    case SECOND_WHOLESALE_PERCENTAGE = 'second_wholesale_percentage';
    case THIRD_WHOLESALE_PERCENTAGE = 'third_wholesale_percentage';
    case RETAIL_PERCENTAGE = 'retail_percentage';
}
