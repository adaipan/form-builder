<?php
/**
 * FormBuilder表单生成器
 * Author: xaboy
 * Github: https://github.com/xaboy/form-builder
 */

namespace FormBuilder\components;


use FormBuilder\FormComponentDriver;
use FormBuilder\Helper;
use FormBuilder\traits\component\ComponentOptionsTrait;

/**
 * 选择器组件
 * Class Select
 * @package FormBuilder\components
 * @method $this multiple(Boolean $bool) 是否支持多选, 默认为false
 * @method $this disabled(Boolean $bool) 是否禁用, 默认为false
 * @method $this clearable(Boolean $bool) 是否可以清空选项，只在单选时有效, 默认为false
 * @method $this filterable(Boolean $bool) 是否支持搜索, 默认为false
 * @method $this size(String $size) 选择框大小，可选值为large、small、default或者不填
 * @method $this placeholder(String $placeholder) 选择框默认文字
 * @method $this transfer(String $placeholder) 是否将弹层放置于 body 内，在 Tabs、带有 fixed 的 Table 列内使用时，建议添加此属性，它将不受父级样式影响，从而达到更好的效果, 默认为false
 * @method $this placement(String $placeholder) 弹窗的展开方向，可选值为 bottom 和 top, 默认为bottom
 * @method $this notFoundText(String $text) 当下拉列表为空时显示的内容, 默认为 无匹配数据
 *
 */
class Select extends FormComponentDriver
{
    use ComponentOptionsTrait;

    protected $name = 'select';

    protected $value = [];

    protected $props = [
        'multiple' => false
    ];

    protected static $propsRule = [
        'multiple' => 'boolean',
        'disabled' => 'boolean',
        'clearable' => 'boolean',
        'filterable' => 'boolean',
        'size' => 'string',
        'placeholder' => 'string',
        'transfer' => 'string',
        'placement' => 'string',
        'notFoundText' => 'string',
    ];

    protected function init()
    {
        $this->placeholder('请选择' . $this->title);
    }

    public function value($value)
    {
        if ($value === null) return $this;
        if (!is_array($value))
            $this->value[] = $value;
        else {
            foreach ($value as $v) {
                $this->value[] = (string)$v;
            }
        }
        return $this;
    }


    public function required($message = null, $trigger = 'change')
    {
        $this->setRequired(
            Helper::getVar($message, '请选择' . $this->title),
            $trigger,
            $this->props['multiple'] == true ? 'array' : null
        );
        return $this;
    }

    public function build()
    {
        $options = [];
        foreach ($this->options as $option) {
            if ($option instanceof Option)
                $options[] = $option->build();
        }
        $value = array_unique($this->value);
        if ($this->props['multiple'] != true)
            $value = is_array($value) && isset($value[0]) ? $value[0] : '';
        return [
            'type' => $this->name,
            'field' => $this->field,
            'title' => $this->title,
            'value' => $value,
            'props' => $this->props,
            'options' => $options,
            'validate' => $this->validate
        ];
    }
}