<?php

namespace app\responses\models\select2;

use yii\data\Pagination;

class Select2Pagination
{
    public function __construct(
        public bool $more
    ) {
    }

    public static function getInstanceByDataProvider(Pagination $pagination): static
    {
        $more = $pagination->pageCount * $pagination->pageSize >= $pagination->totalCount && $pagination->page < $pagination->pageCount - 1;
        return new static(
            more: $more,
        );
    }
}
