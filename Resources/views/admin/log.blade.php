<x-table>
    <x-table.thead>
        <x-table.tr class="flex items-center px-1">
            @stack('created_at_th_start')
            <x-table.th class="w-7/12 sm:w-2/12">
                @stack('created_at_th_inside_start')
                <x-sortablelink
                    column="created_at"
                    title="{{ trans('gerencianet::general.log.execution_date') }}"
                />
                @stack('created_at_th_inside_end')
            </x-table.th>
            @stack('created_at_th_end')

            @stack('status_th_start')
            <x-table.th class="w-5/12 sm:w-2/12">
                @stack('status_th_inside_start')
                <x-sortablelink
                    column="status"
                    title="{{ trans_choice('general.statuses', 1) }}"
                />
                @stack('status_th_inside_end')
            </x-table.th>
            @stack('status_th_end')

            @stack('action_th_start')
            <x-table.th class="w-2/12 hidden sm:table-cell">
                @stack('action_th_inside_start')
                <x-sortablelink
                    column="action"
                    title="{{ trans('gerencianet::general.log.action') }}"
                />
                @stack('action_th_inside_end')
            </x-table.th>
            @stack('action_th_end')

            @stack('message_th_start')
            <x-table.th class="w-6/12 hidden sm:table-cell">
                @stack('message_th_inside_start')
                {{ trans('gerencianet::general.log.message') }}
                @stack('message_th_inside_end')
            </x-table.th>
            @stack('message_th_end')
        </x-table.tr>
    </x-table.thead>

    <x-table.tbody>
        @foreach($logs as $log)
            <x-table.tr>
                @stack('created_at_td_start')
                <x-table.td class="w-7/12 sm:w-2/12" kind="cursor-none">
                    @stack('created_at_td_inside_start')
                    <x-date date="{{ $log->created_at }}" :format="$datetime_format" />
                    @stack('created_at_td_inside_end')
                </x-table.td>
                @stack('created_at_td_end')

                @stack('status_td_start')
                <x-table.td class="w-5/12 sm:w-2/12" kind="cursor-none">
                    @stack('status_td_inside_start')
                    <span class="px-2.5 py-1 text-xs font-medium rounded-xl bg-{{ $log->status_label }} text-text-{{ $log->status_label }}">
                        {{ trans('gerencianet::general.log.' . ($log->error == 0 ? 'success' : 'error')) }}
                    </span>
                    @stack('status_td_inside_end')
                </x-table.td>
                @stack('status_td_end')

                @stack('action_td_start')
                <x-table.td class="w-2/12 hidden sm:table-cell" kind="cursor-none">
                    @stack('action_td_inside_start')
                    {{ trans('gerencianet::general.log.' . $log->action) }}
                    @stack('action_td_inside_end')
                </x-table.td>
                @stack('action_td_end')

                @stack('message_td_start')
                <td class="w-6/12 hidden sm:table-cell cursor-default py-4 text-sm break-all">
                    @stack('message_td_inside_start')
                        {{ $log->message }}
                    @stack('message_td_inside_end')
                </td>
                @stack('message_td_end')
            </x-table.tr>
        @endforeach
    </x-table.tbody>
</x-table>

<x-pagination :items="$logs" />
