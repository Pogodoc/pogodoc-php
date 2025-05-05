<?php

namespace Pogodoc\Render\Types;

enum InitializeRenderJobRequestType: string
{
    case Docx = "docx";
    case Xlsx = "xlsx";
    case Pptx = "pptx";
    case Ejs = "ejs";
    case Html = "html";
    case Latex = "latex";
    case React = "react";
}
