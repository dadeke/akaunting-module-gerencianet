<x-layouts.admin>
    <x-slot name="title">{{ $module->getName() }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form
                id="setting"
                method="PATCH"
                :route="['settings.module.update', $module->getAlias()]"
                :model="$setting"
            >
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head
                            title="{{ trans('general.general') }}"
                            description="{!! trans($module->getAlias() . '::general.description') !!}"
                         />
                    </x-slot>

                    <x-slot name="body">
                        @foreach($fields as $field)
                            @php $type = $field['type']; @endphp

                            @switch($type)
                                @case('select')
                                    <x-form.group.select
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :options="$field['values']"
                                        :selected="setting($module->getAlias() . '.' . $field['name'], $field['selected'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('account')
                                    @php
                                        $account = setting($module->getAlias() . '.' . $field['name']);
                                    @endphp

                                    <x-form.group.account
                                        :selected="$account"
                                        :dynamic-attributes="$field['attributes']"
                                        without-add-new
                                    />
                                    @break
                                @case('vendor')
                                    <el-tooltip>
                                        <div slot="content">
                                            {!! trans($field['tooltip']) !!}
                                        </div>
                                        <x-form.group.contact
                                            name="{{ $field['name'] }}"
                                            type="{{ config('type.transaction.expense.contact_type') }}"
                                            not-required
                                        />
                                    </el-tooltip>
                                    @break
                                @default
                                    @php
                                        $componentName = 'form.group.' . $type;
                                    @endphp
                                    @if(array_key_exists('tooltip', $field))
                                        <el-tooltip>
                                            <div slot="content">
                                                {!! trans($field['tooltip']) !!}
                                            </div>
                                    @endif
                                    <x-dynamic-component
                                        :component="$componentName"
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @if(array_key_exists('tooltip', $field))
                                        </el-tooltip>
                                    @endif
                            @endswitch
                        @endforeach

                        <x-form.input.hidden
                            name="module_alias"
                            :value="$module->getAlias()"
                        />
                    </x-slot>
                </x-form.section>

                @can('update-' . $module->getAlias() . '-settings')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
