<?php

namespace app\responses\models\select2;

class Select2Response
{
    /**
     * @param Select2ResponseModel[] $results
     * @param Select2Pagination|null $pagination
     */
    public function __construct(
        public array $results,
        public Select2Pagination|null $pagination = null,
    ) {
    }
}
