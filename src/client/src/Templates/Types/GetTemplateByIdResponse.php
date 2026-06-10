<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;

class GetTemplateByIdResponse extends JsonSerializableType
{
    /**
     * @var string $uuid Unique ID of the template
     */
    #[JsonProperty('uuid')]
    public string $uuid;

    /**
     * @var string $title Title of the template
     */
    #[JsonProperty('title')]
    public string $title;

    /**
     * @var ?string $description Description of the template
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var value-of<GetTemplateByIdResponseType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var array<string> $categories Categories of the template
     */
    #[JsonProperty('categories'), ArrayType(['string'])]
    public array $categories;

    /**
     * @var value-of<GetTemplateByIdResponsePermissions> $permissions Permissions of the template
     */
    #[JsonProperty('permissions')]
    public string $permissions;

    /**
     * @var ?string $preview Preview URL of the template
     */
    #[JsonProperty('preview')]
    public ?string $preview;

    /**
     * @var string $contentId Content ID of the template in S3
     */
    #[JsonProperty('contentId')]
    public string $contentId;

    /**
     * @var ?string $sourceCode Source code of the template
     */
    #[JsonProperty('sourceCode')]
    public ?string $sourceCode;

    /**
     * @var ?array<string, mixed> $sampleData Sample data for the template
     */
    #[JsonProperty('sampleData'), ArrayType(['string' => 'mixed'])]
    public ?array $sampleData;

    /**
     * @param array{
     *   uuid: string,
     *   title: string,
     *   type: value-of<GetTemplateByIdResponseType>,
     *   categories: array<string>,
     *   permissions: value-of<GetTemplateByIdResponsePermissions>,
     *   contentId: string,
     *   description?: ?string,
     *   preview?: ?string,
     *   sourceCode?: ?string,
     *   sampleData?: ?array<string, mixed>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->uuid = $values['uuid'];
        $this->title = $values['title'];
        $this->description = $values['description'] ?? null;
        $this->type = $values['type'];
        $this->categories = $values['categories'];
        $this->permissions = $values['permissions'];
        $this->preview = $values['preview'] ?? null;
        $this->contentId = $values['contentId'];
        $this->sourceCode = $values['sourceCode'] ?? null;
        $this->sampleData = $values['sampleData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
