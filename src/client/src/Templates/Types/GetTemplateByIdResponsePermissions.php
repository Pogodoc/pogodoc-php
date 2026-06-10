<?php

namespace Pogodoc\Templates\Types;

enum GetTemplateByIdResponsePermissions: string
{
    case Public_ = "public";
    case Private_ = "private";
}
