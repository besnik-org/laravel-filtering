<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

class HtmlFields
{

    public static function text($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="text" v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function rich_text_log($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <textarea  v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function rich_text($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="text" v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function number($name, $label): string
    {
        return self::numberField($name, $label);
    }

    /**
     * @param  string  $label
     * @param $name
     * @return string
     */
    public static function numberField($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="number" v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function number_large($name, $label): string
    {
        return self::numberField($name, $label);
    }

    public static function decimal_number($name, $label): string
    {
        return self::numberField($name, $label);
    }

    public static function select_enum($name, string $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="file" v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function select($name, string $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 " >
            <label  class="input-label">{$label}</label>
            <select  v-model="crud.{$name}" class="input-field"  >
              <option value=""> Select </option>
            </select>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function file(string $label, $name): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="file" v-model="crud.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function checkbox($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">  
            <label class="flex items-center">
                <Checkbox v-model="crud.{$name}" v-model:checked="crud.{$name}" />
                <span class="input-label pt-[8px] pl-[5px]">{ $label }</span>
            </label> 
            <InputError class="mt-2" :message="form.errors.{$name}" />
        </div>

EOT;
    }

}