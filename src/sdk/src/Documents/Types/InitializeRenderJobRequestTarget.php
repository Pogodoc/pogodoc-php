<?php

namespace Pogodoc\Documents\Types;

enum InitializeRenderJobRequestTarget: string
{
    case Pdf = "pdf";
    case Html = "html";
    case Docx = "docx";
    case Xlsx = "xlsx";
    case Pptx = "pptx";
    case Png = "png";
    case Jpg = "jpg";
}
