<?php

namespace Pogodoc\Templates\Types;

enum GetUserTemplatesRequestSort: string
{
    case CreatedAtDesc = "createdAt:desc";
    case CreatedAtAsc = "createdAt:asc";
    case UpdatedAtDesc = "updatedAt:desc";
    case UpdatedAtAsc = "updatedAt:asc";
    case TitleAsc = "title:asc";
    case TitleDesc = "title:desc";
}
