<?php

namespace Pogodoc\Templates\Types;

enum SaveCreatedTemplateRequestTemplateInfoType: string
{
    case Docx = "docx";
    case Xlsx = "xlsx";
    case Pptx = "pptx";
    case Ejs = "ejs";
    case Html = "html";
    case Latex = "latex";
    case React = "react";
}
