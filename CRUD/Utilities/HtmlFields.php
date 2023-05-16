<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

class HtmlFields
{

    public static function text($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="text" v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function rich_text_log($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <textarea  v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function rich_text($name, $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="text" v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function number($name, $label): string
    {
        return self::numberField($label, $name);
    }

    /**
     * @param  string  $label
     * @param $name
     * @return string
     */
    private static function numberField(string $label, $name): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="number" v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function number_large($name, $label): string
    {
        return self::numberField($label, $name);
    }

    public static function decimal_number($name, $label): string
    {
        return self::numberField($label, $name);
    }

    public static function select_enum($name, string $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="file" v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    public static function select($name, string $label): string
    {
        return <<<EOT

          <div class=" w-full mb-3 " xmlns="http://www.w3.org/1999/html">
            <label  class="input-label">{$label}</label>
            <select  v-model="form.{$name}" class="input-field"  >
              <option value=""> Select </option>
            </select>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    private static function file(string $label, $name): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">
            <label  class="input-label">{$label}</label>
            <input type="file" v-model="form.{$name}" class="input-field" placeholder="{$label}"/>
            <InputError class="mt-2" :message="form.errors.{$name}" />
          </div>

EOT;
    }

    private static function checkbox(string $label, $name): string
    {
        return <<<EOT

          <div class=" w-full mb-3 ">  
            <label class="flex items-center">
                <Checkbox v-model="form.{$name}" v-model:checked="form.{$name}" />
                <span class="input-label  ">{{$label}}</span>
            </label> 
            <InputError class="mt-2" :message="form.errors.{$name}" />
        </div>

EOT;
    }

}