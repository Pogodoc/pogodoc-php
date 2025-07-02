<?php

namespace Pogodoc\Templates\Types;

enum UpdateTemplateRequestTemplateInfoCategoriesItem: string
{
    case Invoice = "invoice";
    case Mail = "mail";
    case Report = "report";
    case Cv = "cv";
    case Other = "other";
}
