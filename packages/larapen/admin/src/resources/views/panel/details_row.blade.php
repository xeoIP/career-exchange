<div class="container m-t-10 m-b-10">
    <div class="row">
        <div class="col-md-12">

            @if (count($translations))
                <p>
                    Translations of this {!! $xPanel->entity_name !!}:
                </p>
                <table class="table table-condensed table-bordered" style="m-t-10">
                    <thead>
                    <tr>
                        <th>
                            Language
                        </th>
                        {{-- Table columns --}}
                        @foreach ($xPanel->columns as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach

                        @if ( $xPanel->hasAccess('update') or $xPanel->hasAccess('delete') )
                            <th>{{ trans('admin::messages.actions') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($translations as $key => $entry)
                        {{-- Don't try to show missing or deactivated languages translations --}}
                        @if (!isset($entry->language) or !isset($entry->language->active) or $entry->language->active != 1)
                            @continue;
                        @endif
                        <tr>
                            <td>
                                {{ $entry->language->name }}
                            </td>

                            @foreach ($xPanel->columns as $column)
                                @if (isset($column['type']) && $column['type']=='select_multiple')
                                    {{-- relationships with pivot table (n-n) --}}
                                    <td><?php
                                        $results = $entry->{$column['entity']}()->getResults();
                                        if ($results && $results->count()) {
                                            $results_array = $results->lists($column['attribute'], 'id');
                                            echo implode(', ', $results_array->toArray());
                                        }
                                        else
                                        {
                                            echo '-';
                                        }
                                        ?></td>
                                @elseif (isset($column['type']) && $column['type']=='select')
                                    {{-- single relationships (1-1, 1-n) --}}
                                    <td><?php
                                        if ($entry->{$column['entity']}()->getResults()) {
                                            echo $entry->{$column['entity']}()->getResults()->{$column['attribute']};
                                        }
                                        ?></td>
								@elseif (isset($column['on_display']) && $column['on_display']=='checkbox')
                                    {{-- checkbox display object attribute --}}
                                    <td>{!! checkboxDisplay($entry->{$column['name']}) !!}</td>
                                @else
                                    {{-- regular object attribute --}}
                                    <td>{{ str_limit(strip_tags($entry->{$column['name']}), 80, "[...]") }}</td>
                                @endif

                            @endforeach

                            @if ( $xPanel->hasAccess('update') or $xPanel->hasAccess('delete') )
                                <td>
                                    @if ($xPanel->hasAccess('update'))
                                        <a href="{{ str_replace('/'.$original_entry->id, '/'.$entry->id, str_replace('details', 'edit', Request::url())) }}" class="btn btn-xs btn-default">
                                            <i class="fa fa-edit"></i> {{ trans('admin::messages.edit') }}
                                        </a>
                                    @endif
                                    @if ($xPanel->hasAccess('delete'))
                                        <a href="{{ str_replace('/'.$original_entry->id, '/'.$entry->id, str_replace('details', '', Request::url())) }}" class="btn btn-xs btn-default" data-button-type="delete">
                                            <i class="fa fa-trash"></i> {{ trans('admin::messages.delete') }}
                                        </a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No translations available. <br><br>
            @endif

            @if ($languages_to_translate_in->count())
                Add translation to:
                @foreach($languages_to_translate_in as $lang)
                    <a class="btn btn-xs btn-default" href="{{ str_replace('details', 'translate/'.$lang->abbr, Request::url()) }}"><i class="fa fa-plus"></i> {{ $lang->name }}</a>
                @endforeach
            @endif
        </div>
    </div>
</div>
