<?php

namespace Pogodoc\Templates\Types;

enum GetUserTemplatesRequestCategory: string
{
    case Invoice = "invoice";
    case Mail = "mail";
    case Report = "report";
    case Cv = "cv";
    case Receipt = "receipt";
    case Order = "order";
    case Contract = "contract";
    case Certificate = "certificate";
    case Statement = "statement";
    case Brochure = "brochure";
    case Warranty = "warranty";
    case Poster = "poster";
    case Menu = "menu";
    case Catalog = "catalog";
    case Packaging = "packaging";
    case Advertisement = "advertisement";
    case Other = "other";
    case Favorite = "favorite";
}
