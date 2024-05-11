<?php

namespace app\widgets;

use app\db\AbstractPgModel;
use yii\web\JsExpression;

class Select2 extends \kartik\select2\Select2
{
    public bool $initSelection = true;

    public function init(): void
    {
        parent::init();
        $value = $this->model?->{$this->attribute ?: ''} ?? null;
        if ($this->model instanceof AbstractPgModel) {
            $value = $this->model->getRawAttribute($this->attribute ?? '') ?? null;
        }
        if ($value && $this->initSelection) {
            if ($this->pluginOptions['ajax'] ?? null) {
                $url = ($this->pluginOptions['ajax'] ?? [])['url'] ?? '';
                $this->pluginOptions['initSelection'] = new JsExpression(
                    <<<JS
                    function (element, callback) {
                        var id = $(element).val();
                        var url = '$url';
                        if(id !== '') {
                            $.ajax(url, {
                                data: {id: id},
                                dataType: 'json',
                                beforeSend: function() {
                                    // Render loader before ajax request
                                    const resultWrapper = element.next().find('.select2-selection__rendered');
                                    const loader = document.createElement('div');
                                    const loaderContent = document.createElement('div');
                                    loader.className = 'loader';
                                    loaderContent.className = 'loader-content';
                                    loader.append(loaderContent);
                                    
                                    if (resultWrapper) {
                                        resultWrapper.append(loader);
                                    }
                                },
                                success: function() {
                                    // Remove loader after success request
                                    const loader = element.next().find('.loader');
                                    if (loader.length > 0) {
                                        loader.remove();
                                    }
                                },
                                error: function() {
                                    // Remove loader after error request
                                    const loader = element.next().find('.loader');
                                    if (loader.length > 0) {
                                        loader.remove();
                                    }
                                }
                            }).done(function(data) {
                                if (!data.results.length) {
                                   return;
                                }
                                if ('children' in data.results[0] && data.results[0].children && data.results[0].children.length > 0) {
                                    callback(data.results[0].children[0]);
                                } else {
                                    callback(data.results);

                                    element.find('option').each(function(){
                                        let that = this;
                                        
                                        data.results.map((item) => {
                                            item.id = String(item.id);
                                            return item;
                                        });
                                        let item = data.results.filter(function (item) {
                                            return item.id === $(that).val();
                                        })[0];

                                        if (item) {
                                            if (item.hasOwnProperty('text')) {
                                                $(this).html(item.text);
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                JS,
                );
            } else {
                $this->options['value'] = $value;
            }
        }
    }
}
