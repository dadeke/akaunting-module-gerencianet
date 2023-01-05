<x-table>
    <x-table.thead>
        <x-table.tr class="flex items-center px-1">
            @stack('due_at_and_issued_at_th_start')
            <x-table.th class="w-4/12 table-title hidden sm:table-cell">
                @stack('due_at_th_start')
                <x-slot name="first">
                    @stack('due_at_th_inside_start')
                    <x-sortablelink
                        column="document.due_at"
                        title="{{ trans('invoices.due_date') }}"
                    />
                    @stack('due_at_th_inside_end')
                </x-slot>
                @stack('due_at_th_end')

                @stack('issued_at_th_start')
                <x-slot name="second">
                    @stack('issued_at_th_inside_start')
                    <x-sortablelink
                        column="document.issued_at"
                        title="{{ trans('invoices.invoice_date') }}"
                    />
                    @stack('issued_at_th_inside_end')
                </x-slot>
                @stack('issued_at_th_end')
            </x-table.th>
            @stack('due_at_and_issued_at_th_end')

            @stack('status_th_start')
            <x-table.th class="w-3/12 table-title hidden sm:table-cell">
                @stack('status_th_inside_start')
                <x-sortablelink
                    column="document.status"
                    title="{{ trans_choice('general.statuses', 1) }}"
                />
                @stack('status_th_inside_end')
            </x-table.th>
            @stack('status_th_end')

            @stack('contact_name_ane_document_number_th_start')
            <x-table.th class="w-6/12 sm:w-3/12 table-title">
                @stack('contact_name_th_start')
                <x-slot name="first">
                    @stack('contact_name_th_inside_start')
                    <x-sortablelink
                        column="document.contact_name"
                        title="{{ trans_choice('general.customers', 1) }}"
                    />
                    @stack('contact_name_th_inside_end')
                </x-slot>
                @stack('contact_name_th_end')

                @stack('document_number_th_start')
                <x-slot name="second">
                    @stack('document_number_th_inside_start')
                    <x-sortablelink
                        column="document.document_number"
                        title="{{ trans_choice('general.numbers', 1) }}"
                    />
                    @stack('document_number_th_inside_end')
                </x-slot>
                @stack('document_number_th_end')
            </x-table.th>
            @stack('contact_name_ane_document_number_th_end')

            @stack('amount_th_start')
            <x-table.th class="w-6/12 sm:w-2/12" kind="amount">
                @stack('amount_th_inside_start')
                <x-sortablelink
                    column="document.amount"
                    title="{{ trans('general.amount') }}"
                />
                @stack('amount_th_inside_end')
            </x-table.th>
            @stack('amount_th_end')
        </x-table.tr>
    </x-table.thead>

    <x-table.tbody>
        @foreach($transactions as $transaction)
            @php
            $item = $transaction->document;
            $url = URL::signedRoute('signed.invoices.show', [$item->id]);
            @endphp
            <x-table.tr onclick="window.open('{{ $url }}', '_blank');">
                @stack('due_at_and_issued_at_td_start')
                <x-table.td class="w-4/12 table-title hidden sm:table-cell">
                    @stack('due_at_td_start')
                    <x-slot name="first" class="font-bold truncate" override="class">
                        @stack('due_at_td_inside_start')
                        <x-date
                            :date="$item->due_at"
                            function="diffForHumans"
                        />
                        @stack('due_at_td_inside_end')
                    </x-slot>
                    @stack('due_at_td_end')

                    @stack('issued_at_td_start')
                    <x-slot name="second">
                        @stack('issued_at_td_inside_start')
                        <x-date date="{{ $item->issued_at }}" />
                        @stack('issued_at_td_inside_end')
                    </x-slot>
                    @stack('issued_at_td_end')
                </x-table.td>
                @stack('due_at_and_issued_at_td_end')

                @stack('status_td_start')
                    <x-table.td class="w-3/12 table-title hidden sm:table-cell">
                        @stack('status_td_inside_start')
                        <span class="px-2.5 py-1 text-xs font-medium rounded-xl bg-{{ $item->status_label }} text-text-{{ $item->status_label }}">
                            {{ trans('documents.statuses.' . $item->status) }}
                        </span>
                        @stack('status_td_inside_end')
                    </x-table.td>
                @stack('status_td_end')

                @stack('contact_name_and_document_number_td_start')
                <x-table.td class="w-6/12 sm:w-3/12 table-title">
                    @stack('contact_name_td_start')
                    <x-slot name="first">
                        @stack('contact_name_td_inside_start')
                        {{ $item->contact_name }}
                        @stack('contact_name_td_inside_end')
                    </x-slot>
                    @stack('contact_name_td_end')

                    @stack('document_number_td_start')
                    <x-slot
                        name="second"
                        class="w-20 font-normal group"
                        data-tooltip-target="tooltip-information-{{ $item->id }}"
                        data-tooltip-placement="left"
                        override="class"
                    >
                        @stack('document_number_td_inside_start')
                        <span class="border-black border-b border-dashed">
                            {{ $item->document_number }}
                        </span>

                        <div class="w-28 absolute h-10 -ml-12 -mt-6"></div>
                        @stack('document_number_td_inside_end')

                        <x-documents.index.information
                            :document="$item"
                            :hide-show="false"
                            :show-route="'customers.show'"
                        />
                    </x-slot>
                    @stack('document_number_td_end')
                </x-table.td>
                @stack('contact_name_and_document_number_td_end')

                @stack('amount_td_start')
                <x-table.td class="w-6/12 sm:w-2/12" kind="amount">
                    @stack('amount_td_inside_start')
                    <x-money
                        :amount="$item->amount"
                        :currency="$item->currency_code"
                        convert
                    />
                    @stack('amount_td_inside_end')
                </x-table.td>

                <x-table.td kind="action">
                    <x-table.actions :model="$transaction" />
                </x-table.td>
                @stack('amount_td_end')
            </x-table.tr>
        @endforeach
    </x-table.tbody>
</x-table>

<x-pagination :items="$transactions" />
