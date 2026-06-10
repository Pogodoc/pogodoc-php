<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Templates\Types\GetUserTemplatesRequestCategory;
use Pogodoc\Templates\Types\GetUserTemplatesRequestType;
use Pogodoc\Templates\Types\GetUserTemplatesRequestSort;

class GetUserTemplatesRequest extends JsonSerializableType
{
    /**
     * @var ?value-of<GetUserTemplatesRequestCategory> $category Category of the template
     */
    public ?string $category;

    /**
     * @var ?string $search Search by title or description
     */
    public ?string $search;

    /**
     * @var ?value-of<GetUserTemplatesRequestType> $type Type of template to be rendered
     */
    public ?string $type;

    /**
     * @var ?value-of<GetUserTemplatesRequestSort> $sort Sort order
     */
    public ?string $sort;

    /**
     * @param array{
     *   category?: ?value-of<GetUserTemplatesRequestCategory>,
     *   search?: ?string,
     *   type?: ?value-of<GetUserTemplatesRequestType>,
     *   sort?: ?value-of<GetUserTemplatesRequestSort>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->category = $values['category'] ?? null;
        $this->search = $values['search'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->sort = $values['sort'] ?? null;
    }
}
