<?php

namespace app\responses\models\select2;

class Select2ResponseModel
{
    /**
     * @param string $id
     * @param string $text
     * @param string|null $html
     * @param bool|null $disabled
     * @param bool|null $selected
     * @param Select2ResponseModel[]|null $children
     * @param string|null $icon
     * @param string|null $description
     * @param object|null $additionalData
     */
    public function __construct(
        public string $id,
        public string $text,
        public string|null $html = null,
        public bool|null $disabled = null,
        public bool|null $selected = null,
        public array|null $children = null,
        public string|null $icon = null,
        public string|null $description = null,
        public object|null $additionalData = null,
    ) {
    }
}
