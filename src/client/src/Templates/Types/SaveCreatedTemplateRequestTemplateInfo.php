<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;

class SaveCreatedTemplateRequestTemplateInfo extends JsonSerializableType
{
    /**
     * @var string $title Title of the template
     */
    #[JsonProperty('title')]
    public string $title;

    /**
     * @var string $description Description of the template
     */
    #[JsonProperty('description')]
    public string $description;

    /**
     * @var value-of<SaveCreatedTemplateRequestTemplateInfoType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var array<string, mixed> $sampleData Sample data for the template
     */
    #[JsonProperty('sampleData'), ArrayType(['string' => 'mixed'])]
    public array $sampleData;

    /**
     * @var ?string $sourceCode
     */
    #[JsonProperty('sourceCode')]
    public ?string $sourceCode;

    /**
     * @var array<value-of<SaveCreatedTemplateRequestTemplateInfoCategoriesItem>> $categories Categories of the template
     */
    #[JsonProperty('categories'), ArrayType(['string'])]
    public array $categories;

    /**
     * @param array{
     *   title: string,
     *   description: string,
     *   type: value-of<SaveCreatedTemplateRequestTemplateInfoType>,
     *   sampleData: array<string, mixed>,
     *   categories: array<value-of<SaveCreatedTemplateRequestTemplateInfoCategoriesItem>>,
     *   sourceCode?: ?string,
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
