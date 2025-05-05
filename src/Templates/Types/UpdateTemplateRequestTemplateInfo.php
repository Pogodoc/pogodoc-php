<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;
use Pogodoc\Core\ArrayType;

class UpdateTemplateRequestTemplateInfo extends SerializableType
{
    /**
     * @var string $title
     */
    #[JsonProperty('title')]
    public string $title;

    /**
     * @var string $description
     */
    #[JsonProperty('description')]
    public string $description;

    /**
     * @var UpdateTemplateRequestTemplateInfoType $type
     */
    #[JsonProperty('type')]
    public UpdateTemplateRequestTemplateInfoType $type;

    /**
     * @var array<string, mixed> $sampleData
     */
    #[JsonProperty('sampleData'), ArrayType(['string' => 'mixed'])]
    public array $sampleData;

    /**
     * @var ?string $sourceCode
     */
    #[JsonProperty('sourceCode')]
    public ?string $sourceCode;

    /**
     * @var array<UpdateTemplateRequestTemplateInfoCategoriesItem> $categories
     */
    #[JsonProperty('categories'), ArrayType([UpdateTemplateRequestTemplateInfoCategoriesItem::class])]
    public array $categories;

    /**
     * @param array{
     *   title: string,
     *   description: string,
     *   type: UpdateTemplateRequestTemplateInfoType,
     *   sampleData: array<string, mixed>,
     *   sourceCode?: ?string,
     *   categories: array<UpdateTemplateRequestTemplateInfoCategoriesItem>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->title = $values['title'];
        $this->description = $values['description'];
        $this->type = $values['type'];
        $this->sampleData = $values['sampleData'];
        $this->sourceCode = $values['sourceCode'] ?? null;
        $this->categories = $values['categories'];
    }
}
